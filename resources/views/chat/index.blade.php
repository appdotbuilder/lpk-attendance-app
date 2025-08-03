@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-20 md:pb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden h-[calc(100vh-200px)]">
        <div class="flex h-full">
            <!-- Conversations Sidebar -->
            <div class="w-full sm:w-80 border-r border-gray-200 flex flex-col {{ $selectedConversation ? 'hidden sm:flex' : '' }}">
                <!-- Header -->
                <div class="p-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <span class="mr-2">💬</span>
                            Chat
                        </h2>
                        <button onclick="toggleNewChatModal()" 
                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Conversations List -->
                <div class="flex-1 overflow-y-auto">
                    @if($conversations->count() > 0)
                        @foreach($conversations as $conversation)
                            @php
                                $otherParticipant = $conversation->participants->where('user_id', '!=', auth()->id())->first();
                                $unreadCount = $conversation->messages()->where('user_id', '!=', auth()->id())->whereNull('read_at')->count();
                            @endphp
                            <a href="{{ route('chat.index', ['conversation' => $conversation->id]) }}" 
                               class="block p-4 hover:bg-gray-50 border-b border-gray-100 transition-colors {{ $selectedConversation && $selectedConversation->id === $conversation->id ? 'bg-blue-50 border-blue-200' : '' }}">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-white font-medium">
                                            {{ $otherParticipant ? substr($otherParticipant->user->name, 0, 1) : 'G' }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $otherParticipant ? $otherParticipant->user->name : 'Group Chat' }}
                                            </p>
                                            @if($conversation->lastMessage)
                                                <p class="text-xs text-gray-500">
                                                    {{ $conversation->lastMessage->created_at->format('H:i') }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm text-gray-500 truncate">
                                                @if($conversation->lastMessage)
                                                    {{ Str::limit($conversation->lastMessage->message, 40) }}
                                                @else
                                                    Start a conversation...
                                                @endif
                                            </p>
                                            @if($unreadCount > 0)
                                                <span class="inline-flex items-center justify-center w-5 h-5 bg-blue-600 text-white text-xs rounded-full">
                                                    {{ $unreadCount }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="text-center py-8 px-4">
                            <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <span class="text-2xl">💬</span>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No conversations yet</h3>
                            <p class="text-gray-500 mb-4">Start chatting with your colleagues</p>
                            <button onclick="toggleNewChatModal()" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                                <span class="mr-2">💬</span>
                                Start New Chat
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Chat Area -->
            <div class="flex-1 flex flex-col {{ !$selectedConversation ? 'hidden sm:flex' : '' }}">
                @if($selectedConversation)
                    @php
                        $otherParticipant = $selectedConversation->participants->where('user_id', '!=', auth()->id())->first();
                    @endphp
                    
                    <!-- Chat Header -->
                    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <button onclick="goBackToConversations()" class="sm:hidden p-2 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-medium">
                                    {{ $otherParticipant ? substr($otherParticipant->user->name, 0, 1) : 'G' }}
                                </span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">
                                    {{ $otherParticipant ? $otherParticipant->user->name : 'Group Chat' }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    {{ $otherParticipant ? ucfirst($otherParticipant->user->role) : 'Group' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Messages Area -->
                    <div id="messagesArea" class="flex-1 overflow-y-auto p-4 space-y-4">
                        @foreach($messages as $message)
                            <div class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-xs lg:max-w-md">
                                    @if($message->user_id !== auth()->id())
                                        <div class="flex items-center space-x-2 mb-1">
                                            <div class="w-6 h-6 bg-gray-400 rounded-full flex items-center justify-center">
                                                <span class="text-white text-xs">
                                                    {{ substr($message->user->name, 0, 1) }}
                                                </span>
                                            </div>
                                            <span class="text-xs text-gray-500">{{ $message->user->name }}</span>
                                        </div>
                                    @endif
                                    <div class="rounded-2xl px-4 py-2 {{ $message->user_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-900' }}">
                                        <p class="text-sm">{{ $message->message }}</p>
                                        <p class="text-xs {{ $message->user_id === auth()->id() ? 'text-blue-100' : 'text-gray-500' }} mt-1">
                                            {{ $message->created_at->format('H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Message Input -->
                    <div class="p-4 border-t border-gray-200">
                        <form action="{{ route('chat.store') }}" method="POST" class="flex items-center space-x-2">
                            @csrf
                            <input type="hidden" name="conversation_id" value="{{ $selectedConversation->id }}">
                            <div class="flex-1">
                                <input type="text" name="message" 
                                       placeholder="Type your message..." 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       required>
                            </div>
                            <button type="submit" 
                                    class="p-2 bg-blue-600 hover:bg-blue-700 text-white rounded-full transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                            </button>
                        </form>
                    </div>
                @else
                    <!-- No Conversation Selected -->
                    <div class="flex-1 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-24 h-24 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <span class="text-4xl">💬</span>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Select a conversation</h3>
                            <p class="text-gray-500">Choose a conversation from the sidebar to start chatting</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- New Chat Modal -->
<div id="newChatModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Start New Chat</h3>
                <button onclick="toggleNewChatModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('chat.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label for="recipient_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Select Recipient
                    </label>
                    <select name="recipient_id" id="recipient_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">Choose a person...</option>
                        @foreach($availableUsers as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }} ({{ ucfirst($user->role) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="initial_message" class="block text-sm font-medium text-gray-700 mb-2">
                        Message
                    </label>
                    <textarea name="message" id="initial_message" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Type your message..."
                              required></textarea>
                </div>
                
                <div class="flex space-x-3">
                    <button type="submit" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium transition-colors">
                        Send Message
                    </button>
                    <button type="button" onclick="toggleNewChatModal()" 
                            class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-lg font-medium transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleNewChatModal() {
        const modal = document.getElementById('newChatModal');
        modal.classList.toggle('hidden');
    }

    function goBackToConversations() {
        window.location.href = '{{ route("chat.index") }}';
    }

    // Auto-scroll to bottom of messages
    function scrollToBottom() {
        const messagesArea = document.getElementById('messagesArea');
        if (messagesArea) {
            messagesArea.scrollTop = messagesArea.scrollHeight;
        }
    }

    // Scroll to bottom on page load
    document.addEventListener('DOMContentLoaded', function() {
        scrollToBottom();
    });

    // Auto-refresh messages every 5 seconds (basic polling)
    @if($selectedConversation)
        setInterval(function() {
            fetch('{{ route("chat.show", $selectedConversation->id) }}?conversation_id={{ $selectedConversation->id }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.messages) {
                        updateMessages(data.messages);
                    }
                })
                .catch(error => console.log('Error fetching messages:', error));
        }, 5000);

        function updateMessages(messages) {
            const messagesArea = document.getElementById('messagesArea');
            const currentUserId = {{ auth()->id() }};
            
            messagesArea.innerHTML = '';
            
            messages.forEach(message => {
                const messageDiv = document.createElement('div');
                messageDiv.className = `flex ${message.is_own ? 'justify-end' : 'justify-start'}`;
                
                messageDiv.innerHTML = `
                    <div class="max-w-xs lg:max-w-md">
                        ${!message.is_own ? `
                            <div class="flex items-center space-x-2 mb-1">
                                <div class="w-6 h-6 bg-gray-400 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs">${message.user.name.charAt(0)}</span>
                                </div>
                                <span class="text-xs text-gray-500">${message.user.name}</span>
                            </div>
                        ` : ''}
                        <div class="rounded-2xl px-4 py-2 ${message.is_own ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-900'}">
                            <p class="text-sm">${message.message}</p>
                            <p class="text-xs ${message.is_own ? 'text-blue-100' : 'text-gray-500'} mt-1">
                                ${message.created_at}
                            </p>
                        </div>
                    </div>
                `;
                
                messagesArea.appendChild(messageDiv);
            });
            
            scrollToBottom();
        }
    @endif
</script>
@endpush
@endsection