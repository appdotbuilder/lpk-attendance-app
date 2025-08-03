<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display the chat interface.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get conversations for the current user
        $conversations = ChatConversation::whereHas('participants', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['participants.user', 'lastMessage'])
        ->orderBy('updated_at', 'desc')
        ->get();

        // Get selected conversation
        $selectedConversation = null;
        $messages = collect();
        
        if ($request->has('conversation')) {
            $selectedConversation = ChatConversation::with(['participants.user'])
                ->whereHas('participants', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->find($request->conversation);
                
            if ($selectedConversation) {
                $messages = ChatMessage::where('conversation_id', $selectedConversation->id)
                    ->with('user')
                    ->orderBy('created_at', 'asc')
                    ->get();
                    
                // Mark messages as read
                ChatMessage::where('conversation_id', $selectedConversation->id)
                    ->where('user_id', '!=', $user->id)
                    ->whereNull('read_at')
                    ->update(['read_at' => now()]);
            }
        }

        // Get available users for new conversations
        $availableUsers = User::where('id', '!=', $user->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('chat.index', [
            'conversations' => $conversations,
            'selectedConversation' => $selectedConversation,
            'messages' => $messages,
            'availableUsers' => $availableUsers,
        ]);
    }

    /**
     * Store a new message.
     */
    public function store(Request $request)
    {
        $request->validate([
            'conversation_id' => 'nullable|exists:chat_conversations,id',
            'recipient_id' => 'required_without:conversation_id|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        $user = Auth::user();
        $conversation = null;

        if ($request->conversation_id) {
            // Use existing conversation
            $conversation = ChatConversation::whereHas('participants', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->find($request->conversation_id);
        } else {
            // Create new conversation with recipient
            $recipient = User::find($request->recipient_id);
            
            if (!$recipient) {
                return back()->with('error', 'Recipient not found.');
            }

            // Check if conversation already exists between these users
            $existingConversation = ChatConversation::whereHas('participants', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereHas('participants', function ($query) use ($recipient) {
                $query->where('user_id', $recipient->id);
            })
            ->where('type', 'direct')
            ->first();

            if ($existingConversation) {
                $conversation = $existingConversation;
            } else {
                // Create new conversation
                $conversation = ChatConversation::create([
                    'name' => null, // Direct conversations don't have names
                    'type' => 'direct',
                    'created_by' => $user->id,
                ]);

                // Add participants
                $conversation->participants()->create(['user_id' => $user->id]);
                $conversation->participants()->create(['user_id' => $recipient->id]);
            }
        }

        if (!$conversation) {
            return back()->with('error', 'Unable to send message.');
        }

        // Create the message
        $message = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'message' => $request->message,
        ]);

        // Update conversation timestamp
        $conversation->touch();

        return redirect()->route('chat.index', ['conversation' => $conversation->id])
            ->with('success', 'Message sent successfully!');
    }

    /**
     * Show messages for a specific conversation (AJAX endpoint).
     */
    public function show(Request $request, $id = null)
    {
        // If this is an AJAX request for messages
        if ($request->ajax() && $request->has('conversation_id')) {
            $request->validate([
                'conversation_id' => 'required|exists:chat_conversations,id',
            ]);

            $user = Auth::user();
            
            $conversation = ChatConversation::whereHas('participants', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->find($request->conversation_id);

            if (!$conversation) {
                return response()->json(['error' => 'Conversation not found'], 404);
            }

            $messages = ChatMessage::where('conversation_id', $conversation->id)
                ->with('user')
                ->orderBy('created_at', 'asc')
                ->get();

            // Mark messages as read
            ChatMessage::where('conversation_id', $conversation->id)
                ->where('user_id', '!=', $user->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            return response()->json([
                'messages' => $messages->map(function ($message) use ($user) {
                    return [
                        'id' => $message->id,
                        'message' => $message->message,
                        'user' => [
                            'id' => $message->user->id,
                            'name' => $message->user->name,
                        ],
                        'is_own' => $message->user_id === $user->id,
                        'created_at' => $message->created_at->format('H:i'),
                        'created_at_full' => $message->created_at->format('M j, Y H:i'),
                    ];
                })
            ]);
        }

        // Regular show method - redirect to index with conversation
        if ($id) {
            return redirect()->route('chat.index', ['conversation' => $id]);
        }

        return redirect()->route('chat.index');
    }
}