@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">

        <div class="px-6 py-4 border-b bg-white shadow-sm">
      <div class="flex items-center justify-between">
        <!-- Left Section -->
        <div>
          <h1 class="text-xl font-semibold text-gray-800">Student Details</h1>
          <nav class="text-sm text-gray-500 mt-1">
            <span class="hover:text-blue-600 cursor-pointer">Dashboard</span>
            <span class="mx-1">/</span>
            <span class="hover:text-blue-600 cursor-pointer">Student</span>
            <span class="mx-1">/</span>
            <span class="text-gray-700 font-medium">Student Details</span>
          </nav>
        </div>

        <!-- Right Section -->
        <div class="flex gap-3">
          <button
            id="openLoginModal"
            class="border border-gray-300 rounded-md px-4 py-2 text-sm hover:bg-gray-100"
          >
            Login Details
          </button>
          <!-- <button
          class="bg-blue-600 text-white rounded-md px-4 py-2 text-sm hover:bg-blue-700"
        >
          Edit Student
        </button> -->
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Left Section -->
      <div class="space-y-6">
        <!-- Profile Card -->
        <div class="bg-white rounded-lg shadow-sm p-5">
          <div class="flex items-center gap-4">
            <div
              class="w-24 h-24 bg-gray-200 rounded-md flex items-center justify-center text-sm"
            >
              300x300
            </div>
            <div>
              <span
                class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-md"
                >● Active</span
              >
              <h2 class="text-lg font-semibold mt-1">Janet Daniel</h2>
              <p class="text-gray-500 text-sm">AD1256589</p>
            </div>
          </div>

          <h3 class="mt-4 font-medium text-gray-800">Basic Information</h3>
          <ul class="text-sm mt-2 space-y-1">
            <li><strong>Roll No:</strong> 35013</li>
            <li><strong>Gender:</strong> Female</li>
            <li><strong>Date of Birth:</strong> 25 Jan 2008</li>
            <li><strong>Blood Group:</strong> O +ve</li>
            <li><strong>Region:</strong> Christianity</li>
            <li><strong>Caste:</strong> Catholic</li>
            <li><strong>Category:</strong> OBC</li>
            <li><strong>Mother Tongue:</strong> English</li>
            <li>
              <strong>Language:</strong>
              <span class="bg-gray-100 px-2 py-0.5 text-xs rounded-md mr-1"
                >English</span
              >
              <span class="bg-gray-100 px-2 py-0.5 text-xs rounded-md"
                >Spanish</span
              >
            </li>
          </ul>

          <!-- Add Fees Button -->
          <!-- <button
            onclick="openAddFeesModal()"
            class="w-full mt-4 bg-blue-600 text-white text-sm py-2 rounded-md hover:bg-blue-700"
          >
            Add Fees
          </button> -->
        </div>

        <!-- Primary Contact Info -->
        <div class="bg-white rounded-lg shadow-sm p-5">
          <h3 class="font-medium text-gray-800 mb-2">Primary Contact Info</h3>
          <div class="text-sm space-y-2">
            <p class="flex items-center gap-2">
              <span>📞</span> +1 46548 84498
            </p>
            <p class="flex items-center gap-2">
              <span>📧</span> jan@example.com
            </p>
          </div>
        </div>

        <!-- Sibling Info -->
        <div class="bg-white rounded-lg shadow-sm p-5">
          <h3 class="font-medium text-gray-800 mb-3">Sibling Information</h3>
          <div class="space-y-2">
            <div class="flex items-center gap-3 bg-gray-50 p-2 rounded-md">
              <div
                class="w-12 h-12 bg-gray-200 rounded-md flex items-center justify-center text-xs"
              >
                300x300
              </div>
              <div>
                <p class="font-medium text-gray-700">Ralph Claudia</p>
                <p class="text-xs text-gray-500">III, B</p>
              </div>
            </div>
            <div class="flex items-center gap-3 bg-gray-50 p-2 rounded-md">
              <div
                class="w-12 h-12 bg-gray-200 rounded-md flex items-center justify-center text-xs"
              >
                300x300
              </div>
              <div>
                <p class="font-medium text-gray-700">Julie Scott</p>
                <p class="text-xs text-gray-500">V, A</p>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-5 w-[380px]">
          <!-- Tabs Header -->
          <div class="flex gap-8 text-sm border-b border-gray-200 mb-4">
            <button
              id="hostelTab"
              class="text-gray-600 pb-2 border-b-2 border-transparent font-medium focus:outline-none"
              onclick="showTab('hostel')"
            >
              Hostel
            </button>
            <button
              id="transportTab"
              class="text-blue-600 border-b-2 border-blue-600 pb-2 font-medium focus:outline-none"
              onclick="showTab('transport')"
            >
              Transportation
            </button>
          </div>

          <!-- Hostel Info -->
          <div id="hostelContent" class="hidden">
            <div>
              <p class="font-semibold text-gray-800">HI-Hostel</p>
              <p class="text-sm text-gray-500 mt-1">Room No: 25</p>
              <p class="text-sm text-gray-500">Floor: 2nd</p>
            </div>
          </div>

          <!-- Transport Info -->
          <div id="transportContent" class="block">
            <div class="flex items-center gap-3 mb-4">
              <div class="bg-blue-50 p-2 rounded-md">🚍</div>
              <div>
                <p class="text-xs text-gray-500 font-medium">Route</p>
                <p class="font-semibold text-gray-800 text-sm">Newyork</p>
              </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <p class="text-xs text-gray-500 font-medium">Bus Number</p>
                <p class="font-semibold text-gray-800 text-sm">AM 54548</p>
              </div>
              <div>
                <p class="text-xs text-gray-500 font-medium">Pickup Point</p>
                <p class="font-semibold text-gray-800 text-sm">Cincinatti</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Tab Switch JS -->
        <script>
          function showTab(tab) {
            const hostelTab = document.getElementById("hostelTab");
            const transportTab = document.getElementById("transportTab");
            const hostelContent = document.getElementById("hostelContent");
            const transportContent =
              document.getElementById("transportContent");

            if (tab === "hostel") {
              hostelTab.classList.add("text-blue-600", "border-blue-600");
              hostelTab.classList.remove("text-gray-600", "border-transparent");
              transportTab.classList.add("text-gray-600", "border-transparent");
              transportTab.classList.remove("text-blue-600", "border-blue-600");
              hostelContent.classList.remove("hidden");
              transportContent.classList.add("hidden");
            } else {
              transportTab.classList.add("text-blue-600", "border-blue-600");
              transportTab.classList.remove(
                "text-gray-600",
                "border-transparent"
              );
              hostelTab.classList.add("text-gray-600", "border-transparent");
              hostelTab.classList.remove("text-blue-600", "border-blue-600");
              hostelContent.classList.add("hidden");
              transportContent.classList.remove("hidden");
            }
          }
        </script>
      </div>

      <!-- Right Section -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Tabs -->
        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow-sm p-4 flex gap-4 text-sm">
          <button
            id="studentTabBtn"
            class="tab-btn text-gray-600 hover:text-blue-600 pb-1"
            onclick="openTab('student')"
          >
            Student Details
          </button>
          <button
            id="feesTabBtn"
            class="tab-btn text-blue-600 font-medium border-b-2 border-blue-600 pb-1"
            onclick="openTab('fees')"
          >
            Fees
          </button>
        </div>

        <!-- Student Details Tab -->
        <div id="studentTab" class="tab-content hidden">
          <!-- Parents Information -->
          <div class="bg-white rounded-lg shadow-sm p-5">
            <h3 class="font-semibold text-gray-800 mb-4">
              Parents Information
            </h3>
            <div class="space-y-4">
              <!-- Father -->
              <div
                class="flex justify-between items-center border rounded-md p-4 hover:shadow-sm transition"
              >
                <div class="flex items-center gap-4">
                  <div
                    class="w-14 h-14 bg-gray-200 rounded-md flex items-center justify-center text-xs text-gray-500"
                  >
                    300×300
                  </div>
                  <div>
                    <p class="font-medium text-gray-800">Jerald Vicinius</p>
                    <p class="text-sm text-blue-600">Father</p>
                    <p class="text-xs text-gray-500 mt-1">
                      Phone: +1 45545 46464
                    </p>
                    <p class="text-xs text-gray-500">Email: jera@example.com</p>
                  </div>
                </div>
                <button
                  class="bg-gray-800 text-white text-xs px-3 py-1.5 rounded-md hover:bg-gray-700"
                >
                  ⬇
                </button>
              </div>

              <!-- Mother -->
              <div
                class="flex justify-between items-center border rounded-md p-4 hover:shadow-sm transition"
              >
                <div class="flex items-center gap-4">
                  <div
                    class="w-14 h-14 bg-gray-200 rounded-md flex items-center justify-center text-xs text-gray-500"
                  >
                    300×300
                  </div>
                  <div>
                    <p class="font-medium text-gray-800">Roberta Webber</p>
                    <p class="text-sm text-blue-600">Mother</p>
                    <p class="text-xs text-gray-500 mt-1">
                      Phone: +1 46499 24357
                    </p>
                    <p class="text-xs text-gray-500">Email: robe@example.com</p>
                  </div>
                </div>
                <button
                  class="bg-gray-800 text-white text-xs px-3 py-1.5 rounded-md hover:bg-gray-700"
                >
                  ⬇
                </button>
              </div>

              <!-- Guardian -->
              <div
                class="flex justify-between items-center border rounded-md p-4 hover:shadow-sm transition"
              >
                <div class="flex items-center gap-4">
                  <div
                    class="w-14 h-14 bg-gray-200 rounded-md flex items-center justify-center text-xs text-gray-500"
                  >
                    300×300
                  </div>
                  <div>
                    <p class="font-medium text-gray-800">Jerald Vicinius</p>
                    <p class="text-sm text-blue-600">Guardian (Father)</p>
                    <p class="text-xs text-gray-500 mt-1">
                      Phone: +1 45545 46464
                    </p>
                    <p class="text-xs text-gray-500">Email: jera@example.com</p>
                  </div>
                </div>
                <button
                  class="bg-gray-800 text-white text-xs px-3 py-1.5 rounded-md hover:bg-gray-700"
                >
                  ⬇
                </button>
              </div>
            </div>
          </div>

          <!-- Documents & Address Section -->
          <div class="grid md:grid-cols-2 gap-4 mt-5">
            <!-- Documents
            <div class="bg-white rounded-lg shadow-sm p-5">
              <h3 class="font-semibold text-gray-800 mb-3">Documents</h3>
              <div class="space-y-3">
                <div
                  class="flex justify-between items-center border rounded-md p-3 hover:shadow-sm transition"
                >
                  <p class="text-sm flex items-center gap-2">
                    📄 <span>BirthCertificate.pdf</span>
                  </p>
                  <button
                    class="bg-gray-800 text-white text-xs px-3 py-1 rounded-md hover:bg-gray-700"
                  >
                    ⬇
                  </button>
                </div>
                <div
                  class="flex justify-between items-center border rounded-md p-3 hover:shadow-sm transition"
                >
                  <p class="text-sm flex items-center gap-2">
                    📄 <span>Transfer Certificate.pdf</span>
                  </p>
                  <button
                    class="bg-gray-800 text-white text-xs px-3 py-1 rounded-md hover:bg-gray-700"
                  >
                    ⬇
                  </button>
                </div>
              </div>
            </div> -->

            <!-- Address -->
            <div class="bg-white rounded-lg shadow-sm p-5">
              <h3 class="font-semibold text-gray-800 mb-3">Address</h3>
              <div class="space-y-4 text-sm text-gray-700">
                <div>
                  <p class="font-medium text-gray-800">Current Address</p>
                  <p class="text-gray-600">
                    3495 Red Hawk Road, Buffalo Lake, MN 55314
                  </p>
                </div>
                <div>
                  <p class="font-medium text-gray-800">Permanent Address</p>
                  <p class="text-gray-600">
                    3495 Red Hawk Road, Buffalo Lake, MN 55314
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Previous School, Bank, Medical, Other -->
          <div class="grid md:grid-cols-2 gap-4 mt-5">
            <div class="bg-white rounded-lg shadow-sm p-5">
              <h3 class="font-medium mb-3">Bank Details</h3>
              <p class="text-sm"><strong>Bank Name:</strong> Bank of America</p>
              <p class="text-sm"><strong>Branch:</strong> Cincinnati</p>
              <p class="text-sm"><strong>IFSC:</strong> BOA83209832</p>
            </div>
          </div>

          <div class="grid md:grid-cols-2 gap-4">
            <div class="bg-white rounded-lg shadow-sm p-5">
              <h3 class="font-medium mb-3">Medical History</h3>
              <p class="text-sm">
                <strong>Known Allergies:</strong>
                <span
                  class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-md"
                  >Rashes</span
                >
              </p>
              <p class="text-sm mt-1"><strong>Medications:</strong> -</p>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-5">
              <h3 class="font-medium mb-3">Other Info</h3>
              <p class="text-sm text-gray-600">
                Depending on the specific needs of your organization or system,
                additional information may be collected or tracked. It's
                important to ensure that any data collected complies with
                privacy regulations and policies.
              </p>
            </div>
          </div>
        </div>

        <!-- Fees Tab -->
        <div id="feesTab" class="tab-content space-y-6">
          <div class="bg-white rounded-lg shadow-sm p-5">
            <div class="flex flex-wrap justify-between items-center mb-4">
              <h3 class="font-medium text-gray-700">Fees</h3>

              <div class="flex items-center gap-3">
                <div
                  class="flex items-center border rounded-md px-3 py-1 text-sm text-gray-600"
                >
                  <span class="mr-2">📅</span> Year : 2024 / 2025
                </div>
                <div>
                  <input
                    type="text"
                    placeholder="Search"
                    class="border rounded-md px-3 py-1 text-sm focus:ring focus:ring-blue-100"
                  />
                </div>
              </div>
            </div>

            <div class="overflow-x-auto">
              <table class="min-w-full text-sm border rounded-lg" id="feeTable">
                <thead class="bg-gray-100 text-gray-700">
                  <tr>
                    <th class="p-3 border text-left">Fees Group</th>
                    <th class="p-3 border text-left">Fees Code</th>
                    <th class="p-3 border text-left">Due Date</th>
                    <th class="p-3 border text-left">Amount ($)</th>
                    <th class="p-3 border text-left">Status</th>
                    <th class="p-3 border text-left">Ref ID</th>
                    <th class="p-3 border text-left">Mode</th>
                    <th class="p-3 border text-left">Date Paid</th>
                    <th class="p-3 border text-left">Discount ($)</th>
                    <th class="p-3 border text-left">Fine ($)</th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="bg-gray-800 text-white font-medium">
                    <td class="p-3 border">-</td>
                    <td class="p-3 border"></td>
                    <td class="p-3 border"></td>
                    <td class="p-3 border">2000</td>
                    <td class="p-3 border"></td>
                    <td class="p-3 border"></td>
                    <td class="p-3 border"></td>
                    <td class="p-3 border"></td>
                    <td class="p-3 border">200</td>
                    <td class="p-3 border">200</td>
                  </tr>

                  <tr>
                    <td class="p-3 border text-blue-600">
                      Class 1 General <br />
                      <span class="text-xs text-gray-500"
                        >(Dec month Fees)</span
                      >
                    </td>
                    <td class="p-3 border">dec-month-fees</td>
                    <td class="p-3 border">10 Jan 2024</td>
                    <td class="p-3 border">2500</td>
                    <td class="p-3 border text-green-600 font-medium">
                      • Paid
                    </td>
                    <td class="p-3 border">#435443</td>
                    <td class="p-3 border">Cash</td>
                    <td class="p-3 border">05 Jan 2024</td>
                    <td class="p-3 border">10%</td>
                    <td class="p-3 border">0</td>
                  </tr>

                  <tr>
                    <td class="p-3 border text-blue-600">
                      Class 1 General <br />
                      <span class="text-xs text-gray-500"
                        >(Jan month Fees)</span
                      >
                    </td>
                    <td class="p-3 border">jan-month-fees</td>
                    <td class="p-3 border">10 Feb 2024</td>
                    <td class="p-3 border">2000</td>
                    <td class="p-3 border text-green-600 font-medium">
                      • Paid
                    </td>
                    <td class="p-3 border">#435443</td>
                    <td class="p-3 border">Cash</td>
                    <td class="p-3 border">01 Feb 2024</td>
                    <td class="p-3 border">10%</td>
                    <td class="p-3 border">200</td>
                  </tr>

                  <tr>
                    <td class="p-3 border text-blue-600">
                      Class 1 General <br />
                      <span class="text-xs text-gray-500"
                        >(Jul month Fees)</span
                      >
                    </td>
                    <td class="p-3 border">jul-month-fees</td>
                    <td class="p-3 border">10 Aug 2024</td>
                    <td class="p-3 border">2500</td>
                    <td class="p-3 border text-green-600 font-medium">
                      • Paid
                    </td>
                    <td class="p-3 border">#435449</td>
                    <td class="p-3 border">Cash</td>
                    <td class="p-3 border">01 Aug 2024</td>
                    <td class="p-3 border">10%</td>
                    <td class="p-3 border">200</td>
                  </tr>

                  <tr>
                    <td class="p-3 border text-blue-600">
                      Class 1 General <br />
                      <span class="text-xs text-gray-500"
                        >(Mar month Fees)</span
                      >
                    </td>
                    <td class="p-3 border">mar-month-fees</td>
                    <td class="p-3 border">10 Apr 2024</td>
                    <td class="p-3 border">2500</td>
                    <td class="p-3 border text-green-600 font-medium">
                      • Paid
                    </td>
                    <td class="p-3 border">#435453</td>
                    <td class="p-3 border">Cash</td>
                    <td class="p-3 border">03 Apr 2024</td>
                    <td class="p-3 border">10%</td>
                    <td class="p-3 border">0</td>
                  </tr>

                  <tr>
                    <td class="p-3 border text-blue-600">
                      Class 1 General <br />
                      <span class="text-xs text-gray-500"
                        >(Apr month Fees)</span
                      >
                    </td>
                    <td class="p-3 border">apr-month-fees</td>
                    <td class="p-3 border">10 May 2024</td>
                    <td class="p-3 border">2500</td>
                    <td class="p-3 border text-green-600 font-medium">
                      • Paid
                    </td>
                    <td class="p-3 border">#435453</td>
                    <td class="p-3 border">Cash</td>
                    <td class="p-3 border">03 Apr 2024</td>
                    <td class="p-3 border">10%</td>
                    <td class="p-3 border">0</td>
                  </tr>

                  <tr>
                    <td class="p-3 border text-blue-600">
                      Class 1 General <br />
                      <span class="text-xs text-gray-500"
                        >(Jun month Fees)</span
                      >
                    </td>
                    <td class="p-3 border">jun-month-fees</td>
                    <td class="p-3 border">10 Jul 2024</td>
                    <td class="p-3 border">2500</td>
                    <td class="p-3 border text-green-600 font-medium">
                      • Paid
                    </td>
                    <td class="p-3 border">#435450</td>
                    <td class="p-3 border">Cash</td>
                    <td class="p-3 border">05 Jul 2024</td>
                    <td class="p-3 border">10%</td>
                    <td class="p-3 border">200</td>
                  </tr>

                  <tr>
                    <td class="p-3 border text-blue-600">
                      Class 1 General <br />
                      <span class="text-xs text-gray-500"
                        >(May month Fees)</span
                      >
                    </td>
                    <td class="p-3 border">may-month-fees</td>
                    <td class="p-3 border">10 Jun 2024</td>
                    <td class="p-3 border">2500</td>
                    <td class="p-3 border text-green-600 font-medium">
                      • Paid
                    </td>
                    <td class="p-3 border">#435451</td>
                    <td class="p-3 border">Cash</td>
                    <td class="p-3 border">02 Jun 2024</td>
                    <td class="p-3 border">10%</td>
                    <td class="p-3 border">200</td>
                  </tr>

                  <tr>
                    <td class="p-3 border text-blue-600">
                      Class 1 General <br />
                      <span class="text-xs text-gray-500"
                        >(Admission Fees)</span
                      >
                    </td>
                    <td class="p-3 border">admission-fees</td>
                    <td class="p-3 border">25 Mar 2024</td>
                    <td class="p-3 border">2000</td>
                    <td class="p-3 border text-green-600 font-medium">
                      • Paid
                    </td>
                    <td class="p-3 border">#435454</td>
                    <td class="p-3 border">Cash</td>
                    <td class="p-3 border">25 Jan 2024</td>
                    <td class="p-3 border">10%</td>
                    <td class="p-3 border">200</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div
              class="flex justify-end items-center mt-4 text-sm text-gray-600"
            >
              <button class="px-3 py-1 rounded-l-md border">Prev</button>
              <button class="px-3 py-1 bg-blue-600 text-white">1</button>
              <button class="px-3 py-1 rounded-r-md border">Next</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Fees Modal -->
    <div
      id="addFeesModal"
      class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50"
    >
      <div
        class="bg-white w-full max-w-3xl rounded-lg shadow-lg p-6 relative overflow-y-auto max-h-[90vh]"
      >
        <!-- Header -->
        <div class="flex justify-between items-center border-b pb-3 mb-4">
          <h2 class="text-lg font-semibold text-gray-800">
            Collect Fees
            <span
              class="ml-2 bg-blue-100 text-blue-700 text-xs font-semibold px-2 py-0.5 rounded"
            >
              AD124556
            </span>
          </h2>
          <button
            onclick="closeAddFeesModal()"
            class="text-gray-500 hover:text-gray-700 text-xl"
          >
            &times;
          </button>
        </div>

        <!-- Student Info -->
        <div
          class="flex items-center justify-between bg-gray-50 border rounded-md p-4 mb-5"
        >
          <div class="flex items-center gap-3">
            <div
              class="w-12 h-12 bg-gray-200 rounded-md flex items-center justify-center text-xs text-gray-500"
            >
              IMG
            </div>
            <div>
              <p class="font-medium text-gray-800">Janet</p>
              <p class="text-sm text-gray-500">III, A</p>
            </div>
          </div>
          <div class="flex gap-8 text-sm">
            <div>
              <p class="text-gray-500">Total Outstanding</p>
              <p class="font-semibold text-gray-800">2000</p>
            </div>
            <div>
              <p class="text-gray-500">Last Date</p>
              <p class="font-semibold text-gray-800">25 May 2024</p>
            </div>
            <div class="flex items-center gap-2">
              <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
              <p class="text-red-600 font-medium text-sm">Unpaid</p>
            </div>
          </div>
        </div>

        <!-- Form -->
        <div class="grid md:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="text-sm text-gray-600">Fees Group</label>
            <select
              class="w-full border rounded-md p-2 text-sm mt-1 focus:ring-2 focus:ring-blue-500"
            >
              <option>Select</option>
              <option>Class 1 General</option>
            </select>
          </div>

          <div>
            <label class="text-sm text-gray-600">Fees Type</label>
            <select
              class="w-full border rounded-md p-2 text-sm mt-1 focus:ring-2 focus:ring-blue-500"
            >
              <option>Select</option>
              <option>Monthly Fees</option>
            </select>
          </div>

          <div>
            <label class="text-sm text-gray-600">Amount</label>
            <input
              type="number"
              placeholder="Enter Amount"
              class="w-full border rounded-md p-2 text-sm mt-1 focus:ring-2 focus:ring-blue-500"
            />
          </div>

          <div>
            <label class="text-sm text-gray-600">Collection Date</label>
            <input
              type="date"
              class="w-full border rounded-md p-2 text-sm mt-1 focus:ring-2 focus:ring-blue-500"
            />
          </div>

          <div>
            <label class="text-sm text-gray-600">Payment Type</label>
            <select
              class="w-full border rounded-md p-2 text-sm mt-1 focus:ring-2 focus:ring-blue-500"
            >
              <option>Select</option>
              <option>Cash</option>
              <option>Online</option>
              <option>Cheque</option>
            </select>
          </div>

          <div>
            <label class="text-sm text-gray-600">Payment Reference No</label>
            <input
              type="text"
              placeholder="Enter Payment Reference No"
              class="w-full border rounded-md p-2 text-sm mt-1 focus:ring-2 focus:ring-blue-500"
            />
          </div>
        </div>

        <!-- Status -->
        <div class="flex items-center justify-between mt-3">
          <div>
            <label class="text-sm text-gray-600 block">Status</label>
            <p class="text-xs text-gray-400">Change the Status by toggle</p>
          </div>
          <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" class="sr-only peer" />
            <div
              class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-green-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"
            ></div>
          </label>
        </div>

        <!-- Notes -->
        <div class="mt-4">
          <label class="text-sm text-gray-600">Notes</label>
          <textarea
            placeholder="Add Notes"
            rows="3"
            class="w-full border rounded-md p-2 text-sm mt-1 focus:ring-2 focus:ring-blue-500"
          ></textarea>
        </div>

        <!-- Footer Buttons -->
        <div class="flex justify-end items-center gap-3 mt-5">
          <button
            onclick="closeAddFeesModal()"
            class="px-4 py-2 text-sm rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700"
          >
            Cancel
          </button>
          <button
            class="px-4 py-2 text-sm rounded-md bg-blue-600 text-white hover:bg-blue-700"
          >
            Pay Fees
          </button>
        </div>
      </div>
    </div>

    <!-- Login  Background -->
    <div
      id="loginModal"
      class="fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center z-50"
    >
      <!-- Modal Box -->
      <div class="bg-white rounded-lg shadow-lg w-full max-w-sm p-6 relative">
        <!-- Close Button -->
        <button
          id="closeLoginModal"
          class="absolute top-3 right-3 text-gray-500 hover:text-gray-700"
        >
          ✕
        </button>

        <!-- Student Photo -->
        <div class="flex flex-col items-center text-center">
          <div
            class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mb-3 text-sm text-gray-600"
          >
            IMG
          </div>
          <h2 class="text-lg font-semibold">Jerald Vicinius</h2>
          <p class="text-sm text-gray-500">Class 10th • Section A</p>
        </div>

        <!-- Login Credentials -->
        <div class="mt-5">
          <h3 class="text-gray-700 font-medium mb-3 text-center">
            Student Login Credentials
          </h3>
          <table class="w-full text-sm border border-gray-200 rounded-lg">
            <tbody>
              <tr class="border-b border-gray-200">
                <td class="p-2 font-medium text-gray-600">Username</td>
                <td class="p-2 text-gray-800">jerald.vicinius</td>
              </tr>
              <tr>
                <td class="p-2 font-medium text-gray-600">Password</td>
                <td class="p-2 text-gray-800">student@123</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- DataTables CDN -->
    <!-- <link
      rel="stylesheet"
      href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"
    /> -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

    <!-- JSZip and pdfmake (required for Excel & PDF export) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <!-- JavaScript -->
    <script>
      const openBtn = document.getElementById("openLoginModal");
      const modal = document.getElementById("loginModal");
      const closeBtn = document.getElementById("closeLoginModal");

      openBtn.addEventListener("click", () => {
        modal.classList.remove("hidden");
      });

      closeBtn.addEventListener("click", () => {
        modal.classList.add("hidden");
      });

      // Close modal when clicking outside
      modal.addEventListener("click", (e) => {
        if (e.target === modal) modal.classList.add("hidden");
      });
    </script>

    <!-- Modal JS -->
    <script>
      function openAddFeesModal() {
        document.getElementById("addFeesModal").classList.remove("hidden");
      }
      function closeAddFeesModal() {
        document.getElementById("addFeesModal").classList.add("hidden");
      }
    </script>

    <!--for opening tab-->
    <script>
      function openTab(tab) {
        document
          .querySelectorAll(".tab-content")
          .forEach((t) => t.classList.add("hidden"));
        document.getElementById(tab + "Tab").classList.remove("hidden");

        document.querySelectorAll(".tab-btn").forEach((btn) => {
          btn.classList.remove(
            "text-blue-600",
            "font-medium",
            "border-b-2",
            "border-blue-600"
          );
          btn.classList.add("text-gray-600");
        });

        const activeBtn = document.getElementById(tab + "TabBtn");
        activeBtn.classList.add(
          "text-blue-600",
          "font-medium",
          "border-b-2",
          "border-blue-600"
        );
      }
    </script>

    <!--datatables js-->
    <script>
      $(document).ready(function () {
        $("#feeTable").DataTable({
          dom: "Bfrtip",
          buttons: [
            {
              extend: "copyHtml5",
              text: "📋 Copy",
              className:
                "bg-gray-100 border border-gray-300 text-gray-700 px-3 py-1 rounded hover:bg-gray-200",
            },
            {
              extend: "excelHtml5",
              text: "📊 Excel",
              className:
                "bg-green-100 border border-green-300 text-green-700 px-3 py-1 rounded hover:bg-green-200",
            },
            {
              extend: "pdfHtml5",
              text: "📄 PDF",
              className:
                "bg-red-100 border border-red-300 text-red-700 px-3 py-1 rounded hover:bg-red-200",
              orientation: "landscape",
              pageSize: "A4",
              title: "Fees Report",
            },
            {
              extend: "csvHtml5",
              text: "📑 CSV",
              className:
                "bg-blue-100 border border-blue-300 text-blue-700 px-3 py-1 rounded hover:bg-blue-200",
            },
            {
              extend: "print",
              text: "🖨️ Print",
              className:
                "bg-yellow-100 border border-yellow-300 text-yellow-700 px-3 py-1 rounded hover:bg-yellow-200",
            },
            {
              extend: "colvis",
              text: "👁️ Columns",
              className:
                "bg-gray-100 border border-gray-300 text-gray-700 px-3 py-1 rounded hover:bg-gray-200",
            },
          ],
          pageLength: 10,
          lengthMenu: [10, 25, 50, 100],
          order: [],
          language: {
            search: "_INPUT_",
            searchPlaceholder: "Search student...",
          },
        });
      });
    </script>