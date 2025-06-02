<div>
    <div class="flex items-center gap-4 mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search bookings..." class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

   {{-- Date Range Filter --}}
    <div class="bg-white-800 p-4 rounded-lg mb-4">
        <h3 class="text-black text-lg mb-3">Date Range Filter</h3>
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex flex-col">
                <label for="dateFrom" class="black-gray-300 text-sm mb-1">From Date</label>
                <input 
                    type="date" 
                    id="dateFrom"
                    wire:model="tempDateFromFilter" 
                    class="px-4 py-2 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white-900 text-black"
                >
            </div>
            
            <div class="flex flex-col">
                <label for="dateTo" class="black-gray-300 text-sm mb-1">To Date</label>
                <input 
                    type="date" 
                    id="dateTo"
                    wire:model="tempDateToFilter" 
                    class="px-4 py-2 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white-900 text-black"
                >
            </div>
            
            <div class="flex items-end gap-2">
                <button 
                    wire:click="applyDateFilter" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors"
                >
                    Apply Filter
                </button>
                
                @if($dateFromFilter || $dateToFilter)
                <button 
                    wire:click="resetDateFilter" 
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md transition-colors"
                >
                    Clear Filter
                </button>
                @endif
            </div>
        </div>
        
        {{-- Display active filters --}}
        @if($dateFromFilter || $dateToFilter)
            <div class="mt-3 text-gray-300 text-sm">
                <span class="font-semibold">Active Filter:</span>
                @if($dateFromFilter && $dateToFilter)
                    From {{ \Carbon\Carbon::parse($dateFromFilter)->format('M d, Y') }} 
                    to {{ \Carbon\Carbon::parse($dateToFilter)->format('M d, Y') }}
                @elseif($dateFromFilter)
                    From {{ \Carbon\Carbon::parse($dateFromFilter)->format('M d, Y') }} onwards
                @elseif($dateToFilter)
                    Up to {{ \Carbon\Carbon::parse($dateToFilter)->format('M d, Y') }}
                @endif
            </div>
        @endif
    </div>
    <div class="flex items-center gap-4 mb-4">
        <div class="bg-white shadow-md rounded-lg px-3 py-2 h-55 flex items-center w-full">
            <div class="mr-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <h3 class="text-gray-600 text-l mr-1">Total Bookings</h3>
                <p class="text-l font-bold text-blue-600">{{ $bookingsCount }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="overflow-x-auto p-4">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-2">#</th>
                        <th class="border px-4 py-2">Service</th>
                        <th class="border px-4 py-2">Service Type</th>
                        <th class="border px-4 py-2">Date</th>
                        <th class="border px-4 py-2">Time</th>
                        <th class="border px-4 py-2">Status</th>
                        <th class="border px-4 py-2">Remarks</th>
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $index => $booking)
                        <tr>
                            <td class="border px-4 py-2">{{ $index + 1 }}</td>
                            <td class="border px-4 py-2">{{ $booking->service }}</td>
                            <td class="border px-4 py-2">{{ $booking->service_type }}</td>
                            <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</td>
                            <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}</td>
                            <td class="border px-4 py-2">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $booking->status == 'Pending' ? 'bg-yellow-100 text-yellow-800' : 
                                      ($booking->status == 'Approved' ? 'bg-blue-100 text-blue-800' : 
                                      ($booking->status == 'Completed' ? 'bg-green-100 text-green-800' : 
                                       'bg-red-100 text-red-800')) }}">
                                    {{ $booking->status }}
                                </span>
                            </td>
                            <td class="border px-4 py-2">{{ $booking->remarks }}</td>
                            <td class="border px-4 py-2 flex gap-1">
                                <a href="{{ route('appointments.completed.show', $booking->booking_id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-sm">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="border px-4 py-6 text-center text-gray-500">
                                No bookings found for this student.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination Controls -->
        <div class="flex justify-between items-center p-4">
            @if($bookings instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <span class="text-sm text-gray-600">
                    Showing {{ $bookings->firstItem() ?? 0 }} to {{ $bookings->lastItem() ?? 0 }} of {{ $bookings->total() ?? 0 }} rows
                </span>
                <div class="flex items-center space-x-2">
                    <button wire:click="previousPage" class="px-4 py-2 border rounded-md hover:bg-gray-200" @if(!$bookings->hasPages() || $bookings->onFirstPage()) disabled @endif>
                        «
                    </button>
                    @if($bookings->hasPages())
                        @foreach ($bookings->getUrlRange(1, $bookings->lastPage()) as $page => $url)
                            <button wire:click="gotoPage({{ $page }})" class="px-3 py-2 border rounded-md hover:bg-gray-200 @if($bookings->currentPage() == $page) bg-blue-500 text-white @endif">
                                {{ $page }}
                            </button>
                        @endforeach
                    @endif
                    <button wire:click="nextPage" class="px-3 py-2 border rounded-md hover:bg-gray-200" @if(!$bookings->hasPages() || !$bookings->hasMorePages()) disabled @endif>
                        »
                    </button>
                </div>
            @else
                <span class="text-sm text-gray-600">
                    No records found
                </span>
            @endif
        </div>
        </div>
    </div>
    
</div>