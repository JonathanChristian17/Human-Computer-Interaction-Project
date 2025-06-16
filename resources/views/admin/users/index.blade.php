<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-between items-center">
                <h3 class="text-lg font-medium text-[#FFA500]">All Users</h3>
                <a href="{{ route('admin.users.create') }}" class="bg-[#FFA500] hover:bg-[#ff8c1a] text-white font-semibold py-2 px-4 rounded-lg transition-all duration-200">
                    Add New User
                </a>
            </div>

            <div class="bg-[#1F1F1F] overflow-hidden shadow-sm sm:rounded-lg border border-[#333333]">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-[#333333]">
                            <thead class="bg-[#1F1F1F]">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#FFA500] uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#FFA500] uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#FFA500] uppercase tracking-wider">
                                        Role
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#FFA500] uppercase tracking-wider">
                                        Registered
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#FFA500] uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-[#1E1E1E] divide-y divide-[#333333]">
                                @foreach($users as $user)
                                    <tr class="@if($loop->even) bg-[#2A2A2A] @else bg-[#1E1E1E] @endif hover:bg-[#333333] transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-[#E0E0E0]">{{ $user->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-[#E0E0E0]">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($user->role === 'admin') bg-[#FFA500]/10 text-[#FFA500]
                                                @elseif($user->role === 'receptionist') bg-green-500/10 text-green-400
                                                @else bg-blue-500/10 text-blue-400 @endif">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#E0E0E0]">
                                            {{ $user->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a href="{{ route('admin.users.edit', $user) }}" 
                                               class="inline-flex items-center px-3 py-1.5 bg-[#FFA500] hover:bg-[#ff8c1a] rounded-lg text-white text-sm font-medium transition-all duration-200">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Edit
                                            </a>
                                            @if($user->role !== 'admin')
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline needs-confirm" data-confirm-message="Yakin ingin menghapus user ini? Data user akan dihapus permanen.">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="inline-flex items-center px-3 py-1.5 bg-[#1E1E1E] hover:bg-[#2A2A2A] rounded-lg text-red-400 text-sm font-medium transition-all duration-200 border border-red-400">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if($users->isEmpty())
                            <div class="text-center py-4 text-[#E0E0E0]">
                                No users found.
                            </div>
                        @endif
                    </div>

                    @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="mt-4">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 