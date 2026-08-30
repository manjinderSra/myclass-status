@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto bg-gray-50">
        <!-- ===================== PAGE HEADER ===================== -->
        <div class="bg-white border-b shadow-sm sticky top-0 z-10">
            <div class="px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Collect Fees</h1>
                        <nav class="flex items-center text-sm text-gray-600 mt-2 space-x-2" aria-label="Breadcrumb">
                            <a href="#" class="hover:text-blue-600 transition-colors">Dashboard</a>
                            <span class="text-gray-400">/</span>
                            <a href="#" class="hover:text-blue-600 transition-colors">Management</a>
                            <span class="text-gray-400">/</span>
                            <span class="text-gray-900 font-medium">Collect Fees</span>
                        </nav>
                    </div>

                    <!-- Right Tools -->
                    <button 
                        class="flex items-center justify-center w-10 h-10 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors" 
                        title="Refresh" 
                        onclick="window.location.reload()"
                        aria-label="Refresh page"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v6h6M20 20v-6h-6M20 4l-5 5m-6 6l-5 5" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- ===================== MAIN CONTENT ===================== -->
        <div class="px-6 py-6">
            <!-- ===================== FILTER TOOLBAR ===================== -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
                <div class="px-6 py-5">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <h2 class="text-lg font-semibold text-gray-900">Fees List</h2>

                        <form method="GET" action="{{ route('school.collectFee') }}" class="flex flex-wrap items-center gap-3">

                                <!-- Date Range -->
                                {{-- <input 
                                    type="text" 
                                    name="date_range"
                                    value="{{ request('date_range') }}"
                                    class="border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    placeholder="10/28/2025 - 11/03/2025" 
                                /> --}}
                                  <!-- Class Filter -->
            <div class="w-48">
                <select 
                    name="class_id" 
                    id="filterClass"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                >
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>

                                <!-- Search -->
                                <input 
                                    type="search" 
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="Search students..." 
                                    class="border border-gray-300 rounded-lg px-4 py-2.5 text-sm w-64 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                />

                                <!-- Submit button -->
                                <button 
                                    type="submit"
                                    class="flex items-center gap-2 border border-gray-300 rounded-lg px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2H3V4zm0 4h18v12a1 1 0 01-1 1H4a1 1 0 01-1-1V8zm5 4h4m-2-2v4" />
                                    </svg>
                                    Filter
                                </button>

                        </form>

                    </div>
                </div>
            </div>

            <!-- ===================== FEES TABLE ===================== -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="feesTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Adm No</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Roll No</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Student</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Class</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Section</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Amount ($)</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Balance ($)</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Collection Date</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Last Date</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200" id="feesTableBody">
                            @forelse($assignFees as $fee)
                                @php
                                    $student = $fee->student;
                                    $collect = $fee->collectFee;
                                    $isPaid = strtolower($collect->status ?? $fee->status ?? 'unpaid') === 'paid';
                                @endphp

                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-semibold text-blue-600">{{ $student->admission_number ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $student->roll_number ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">{{ $student->first_name ?? '' }} {{ $student->last_name ?? '' }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $student->class->name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $student->section->name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${{ number_format($fee->feeMaster->amount ?? 0, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        ${{ $collect->balance }} 
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ optional($collect)->collection_date ? \Carbon\Carbon::parse($collect->collection_date)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ optional($fee->feeMaster->due_date)->format('d M Y') ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $isPaid ? 'bg-green-100 text-green-800' : (($collect->status ?? $fee->status) === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            <span class="w-1.5 h-1.5 mr-1.5 rounded-full {{ $isPaid ? 'bg-green-600' : (($collect->status ?? $fee->status) === 'pending' ? 'bg-yellow-600' : 'bg-red-600') }}"></span>
                                            {{ ucfirst($collect->status ?? $fee->status ?? 'Unpaid') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <a 
                                                href="{{ route('school.peoples.students.show', $student->id) }}"
                                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:outline-none transition-colors"
                                            >
                                                View
                                            </a>
                                            <button
                                                onclick="openAddFeesModal('{{ $student->admission_number }}', '{{ $fee->id }}')"
                                                {{ $isPaid ? 'disabled' : '' }}
                                                class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition-colors {{ $isPaid ? 'bg-blue-50 text-blue-400 cursor-not-allowed' : 'bg-blue-600 text-white hover:bg-blue-700' }}"
                                            >
                                                Collect
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <p class="text-gray-500 text-sm font-medium">No fee records found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($assignFees->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex justify-center">
                        {{ $assignFees->onEachSide(1)->links('vendor.pagination.custom-tailwind') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- ===================== ADD FEES MODAL ===================== -->
<div id="addFeesModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
            <!-- Modal Header -->
            <div class="bg-white px-6 py-5 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <h2 id="modal-title" class="text-xl font-bold text-gray-900">Collect Fees</h2>
                        <span id="modalAdm" class="inline-flex items-center px-3 py-1 rounded-lg bg-blue-100 text-blue-700 text-sm font-semibold"></span>
                    </div>
                    <button 
                        onclick="closeAddFeesModal()" 
                        class="text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg p-1 transition-colors"
                        aria-label="Close modal"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Student Info Card -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 mx-6 mt-6 p-5 rounded-xl border border-blue-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p id="studentName" class="text-lg font-bold text-gray-900">-</p>
                        <p id="studentClassSection" class="text-sm text-gray-600 mt-1">-</p>
                    </div>
                    <div class="flex items-center gap-8">
                        <div class="text-right">
                            <p class="text-xs font-medium text-gray-600 uppercase tracking-wide mb-1">Total Outstanding</p>
                            <p id="modalOutstanding" class="text-2xl font-bold text-gray-900">$0</p>
                        </div>
                        <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-lg shadow-sm">
                            <span id="modalStatusDot" class="w-3 h-3 rounded-full bg-red-500"></span>
                            <p id="modalStatus" class="text-sm font-semibold text-red-600">Unpaid</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="px-6 py-6">
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Amount <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 text-sm font-medium">$</span>
                            <input 
                                id="amountInput" 
                                type="number" 
                                step="0.01" 
                                placeholder="0.00"
                                class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                            />
                        </div>
                        <p id="amountError" class="text-red-600 text-xs mt-2 hidden flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            Amount cannot be greater than total fees
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Collection Date</label>
                        <input 
                            id="collectionDateInput" 
                            type="date"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Type</label>
                        <select 
                            id="paymentTypeSelect"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        >
                            <option value="">Select payment method</option>
                            <option value="cash">Cash</option>
                            <option value="upi">UPI</option>
                            <option value="cheque">Cheque</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Reference No</label>
                        <input 
                            id="paymentRefInput" 
                            type="text" 
                            placeholder="Enter reference number"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                        />
                    </div>
                </div>

                <div class="mt-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea 
                        id="notesTextarea" 
                        placeholder="Add any additional notes or comments..."
                        rows="3"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"
                    ></textarea>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-gray-200">
                <button 
                    onclick="closeAddFeesModal()"
                    class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
                >
                    Cancel
                </button>
                <button 
                    id="payBtn"
                    class="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors inline-flex items-center"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Pay Fees
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ===================== JAVASCRIPT ===================== -->
@php
$studentData = $assignFees->map(function ($fee) {
    $total = $fee->feeMaster->amount ?? 0;
    $paid = $fee->collectFee->paid_amount ?? 0;
    $balance = $total - $paid;

    return [
        'feeId' => $fee->id,
        'admNo' => $fee->student->admission_number ?? '',
        'rollNo' => $fee->student->roll_number ?? '',
        'name' => trim(($fee->student->first_name ?? '') . ' ' . ($fee->student->last_name ?? '')),
        'class' => $fee->student->class->name ?? '-',
        'section' => $fee->student->section->name ?? '-',
        'amount' => $total,
        'paid' => $paid,
        'balance' => $balance,
        'status' => ucfirst($fee->collectFee->status ?? $fee->status ?? 'Unpaid'),
    ];
});

@endphp

<script>
const students = @json($studentData);

let currentStudent = null;
let currentFeeId = null;

// 🟢 Open modal and populate data
function openAddFeesModal(admNo, feeId) {
    // currentStudent = students.find((s) => s.admNo === admNo && s.feeId == feeId);
    currentStudent = students.find((s) => s.feeId == feeId);


    currentFeeId = feeId;

    if (!currentStudent) {
        console.warn("Student not found for admission no:", admNo);
        return;
    }

    // Basic info
    document.getElementById("modalAdm").textContent = admNo || "-";
    document.getElementById("studentName").textContent = currentStudent.name || "-";
    document.getElementById("studentClassSection").textContent =
        `Class ${currentStudent.class || '-'} (${currentStudent.section || '-'})`;

    // Safely handle numeric values
const balance = Number(currentStudent.balance) || 0;
document.getElementById("modalOutstanding").textContent = `$${balance.toFixed(2)}`;

    document.getElementById("modalStatus").textContent = currentStudent.status || "Unpaid";

    // Status dot + color
    const statusLower = (currentStudent.status || "unpaid").toLowerCase();
    let dotColor = "bg-red-500", textColor = "text-red-600";

    if (statusLower === "paid") {
        dotColor = "bg-green-500";
        textColor = "text-green-600";
    } else if (statusLower === "pending") {
        dotColor = "bg-yellow-500";
        textColor = "text-yellow-600";
    }

    document.getElementById("modalStatusDot").className = `w-3 h-3 rounded-full ${dotColor}`;
    document.getElementById("modalStatus").className = `text-sm font-semibold ${textColor}`;

    // Reset inputs
    document.getElementById("amountInput").value = "";
    document.getElementById("collectionDateInput").value = "";
    document.getElementById("paymentTypeSelect").value = "";
    document.getElementById("paymentRefInput").value = "";
    document.getElementById("notesTextarea").value = "";
    document.getElementById("amountError").classList.add("hidden");

    // Show modal
    document.getElementById("addFeesModal").classList.remove("hidden");
    document.body.style.overflow = "hidden";
}

// 🟡 Close modal
function closeAddFeesModal() {
    document.getElementById("addFeesModal").classList.add("hidden");
    document.body.style.overflow = "auto";
}

// 🟣 Validate amount input in real time
document.getElementById("amountInput").addEventListener("input", function () {
    if (!currentStudent) return;

    const amt = Number(this.value);
    const total = Number(currentStudent.balance) || 0;
    const error = document.getElementById("amountError");

    if (amt > total) {
        error.textContent = "Amount cannot be greater than total fees.";
        error.classList.remove("hidden");
    } else {
        error.classList.add("hidden");
    }
});

// 🔵 Handle payment submission
document.getElementById("payBtn").addEventListener("click", function () {
    if (!currentStudent) return;

    const amt = Number(document.getElementById("amountInput").value || 0);
    const total = Number(currentStudent.amount) || 0;
    const error = document.getElementById("amountError");

    // Validate amount
    if (amt <= 0) {
        error.innerHTML = `⚠️ Please enter a valid amount.`;
        error.classList.remove("hidden");
        return;
    }

    if (amt > total) {
        error.innerHTML = `⚠️ Amount cannot be greater than total fees.`;
        error.classList.remove("hidden");
        return;
    }

    // Gather form data
    const collectionDate = document.getElementById("collectionDateInput").value || null;
    const paymentType = document.getElementById("paymentTypeSelect").value || null;
    const paymentRefNo = document.getElementById("paymentRefInput").value || null;
    const notes = document.getElementById("notesTextarea").value || null;

    // Prevent double submission
    const payBtn = document.getElementById("payBtn");
    payBtn.disabled = true;
    payBtn.innerHTML = `
        <svg class="animate-spin h-5 w-5 mr-2 inline" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0
                  3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg> Processing...`;

    // Send payment request
    fetch(`/collectFee/pay/${currentFeeId}`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            amount: amt,
            collection_date: collectionDate,
            payment_type: paymentType,
            payment_reference_no: paymentRefNo,
            note: notes
        }),
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.success) {
                alert("✅ Payment collected successfully!");
                window.location.reload();
            } else {
                alert(data.message || "Payment failed.");
                payBtn.disabled = false;
                payBtn.innerHTML = `💰 Pay Fees`;
            }
        })
        .catch((err) => {
            console.error(err);
            alert("❌ An error occurred while processing payment.");
            payBtn.disabled = false;
            payBtn.innerHTML = `💰 Pay Fees`;
        });
});

// 🧭 Close modal with ESC key
document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && !document.getElementById("addFeesModal").classList.contains("hidden")) {
        closeAddFeesModal();
    }
});
</script>

<!-- Add these CDN links for date range picker -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
// Initialize date range picker
$(function() {
    $('input[name="date_range"]').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear',
            format: 'MM/DD/YYYY'
        }
    });

    $('input[name="date_range"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    $('input[name="date_range"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
});
</script>
<script>
$(document).ready(function() {
    // ===== AUTO-CLEAR FILTERS ON PAGE REFRESH =====
    const perfData = window.performance.getEntriesByType("navigation")[0];
    
    if (perfData && perfData.type === "reload") {
        // Page was refreshed
        const currentUrl = new URL(window.location.href);
        
        // Check if there are any query parameters (filters)
        if (currentUrl.search) {
            // Redirect to clean URL without filters
            window.location.href = "{{ route('school.collectFee') }}";
        }
    }
    
    // ===== CASCADING CLASS -> SECTION FILTER =====
    const allSections = @json($sections);
    const selectedClassId = "{{ request('class_id') }}";
    const selectedSectionId = "{{ request('section_id') }}";
    
    // Initialize section dropdown based on selected class (on page load)
    if (selectedClassId) {
        updateSectionDropdown(selectedClassId, selectedSectionId);
    }
    
    // Handle class change event
    $('#filterClass').change(function() {
        const classId = $(this).val();
        updateSectionDropdown(classId, '');
    });
    
    // Function to update section dropdown
    function updateSectionDropdown(classId, selectedSection = '') {
        const sectionDropdown = $('#filterSection');
        
        // Clear and reset section dropdown
        sectionDropdown.empty().append('<option value="">All Sections</option>');
        
        if (classId) {
            // Filter sections by class_id
            const filteredSections = allSections.filter(section => 
                section.class_id == classId
            );
            
            if (filteredSections.length === 0) {
                sectionDropdown.append(
                    '<option value="" disabled>No sections available for this class</option>'
                );
            } else {
                filteredSections.forEach(section => {
                    const isSelected = selectedSection == section.id ? 'selected' : '';
                    sectionDropdown.append(
                        `<option value="${section.id}" ${isSelected}>${section.name}</option>`
                    );
                });
            }
        } else {
            // Show all sections if no class selected
            allSections.forEach(section => {
                const isSelected = selectedSection == section.id ? 'selected' : '';
                sectionDropdown.append(
                    `<option value="${section.id}" ${isSelected}>${section.name}</option>`
                );
            });
        }
    }
});
</script>

