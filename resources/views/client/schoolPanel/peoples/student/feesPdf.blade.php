<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student Fees Reportssds</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .school-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 16px;
            color: #666;
            margin-top: 5px;
        }
        .student-info {
            margin-bottom: 20px;
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
        }
        .student-info table {
            width: 100%;
        }
        .student-info td {
            padding: 5px;
        }
        .label {
            font-weight: bold;
            width: 150px;
        }
        .summary-box {
            background: #e3f2fd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .summary-box table {
            width: 100%;
        }
        .summary-box td {
            padding: 8px;
            font-size: 14px;
        }
        .amount {
            font-size: 18px;
            font-weight: bold;
        }
        table.fees-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.fees-table th {
            background: #333;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        table.fees-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        table.fees-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .status {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-paid {
            background: #c8e6c9;
            color: #2e7d32;
        }
        .status-pending {
            background: #ffe0b2;
            color: #e65100;
        }
        .status-unpaid {
            background: #ffcdd2;
            color: #c62828;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        tfoot {
            font-weight: bold;
            background: #f5f5f5;
        }
        tfoot td {
            padding: 10px 8px;
            border-top: 2px solid #333;
        }
            body {
        font-family: 'DejaVu Sans', sans-serif;
    }
    </style>
</head>
<body>
    <div class="header">
        <div class="school-name">{{ $school->name ?? 'School Name' }}</div>
        <div>{{ $school->address ?? '' }}</div>
        <div class="report-title">Student Fees Report</div>
    </div>

    <div class="student-info">
        <table>
            <tr>
                <td class="label">Student Name:</td>
                <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                <td class="label">Admission No:</td>
                <td>{{ $student->admission_number }}</td>
            </tr>
            <tr>
                <td class="label">Class:</td>
                <td>{{ $student->class->name ?? 'N/A' }}</td>
                <td class="label">Section:</td>
                <td>{{ $student->section->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Father Name:</td>
                <td>{{ $student->father_name ?? 'N/A' }}</td>
                <td class="label">Contact:</td>
                <td>{{ $student->primary_contact ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="summary-box">
        <table>
            <tr>
                <td>
                    <div style="color: #666;">Total Fees</div>
                    <div class="amount">&#8377; {{ number_format($totalFees, 2) }}</div>
                </td>
                <td>
                    <div style="color: #2e7d32;">Total Paid</div>
                    <div class="amount" style="color: #2e7d32;">&#8377; {{ number_format($totalPaid, 2) }}</div>
                </td>
                <td>
                    <div style="color: #c62828;">Total Pending</div>
                    <div class="amount" style="color: #c62828;">&#8377; {{ number_format($totalPending, 2) }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="fees-table">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Fee Group</th>
                <th>Fee Type</th>
                <th class="text-right">Amount</th>
                <th class="text-right">Paid</th>
                <th class="text-right">Balance</th>
                <th class="text-center">Due Date</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($studentFees as $index => $fee)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $fee['fee_group'] }}</td>
                <td>{{ $fee['fee_type'] }}</td>
                <td class="text-right">₹{{ number_format($fee['amount'], 2) }}</td>
                <td class="text-right">₹{{ number_format($fee['paid_amount'], 2) }}</td>
                <td class="text-right">₹{{ number_format($fee['balance'], 2) }}</td>
                <td class="text-center">
                    @if($fee['due_date'])
                        {{ \Carbon\Carbon::parse($fee['due_date'])->format('d M Y') }}
                    @else
                        N/A
                    @endif
                </td>
                <td class="text-center">
                    <span class="status status-{{ $fee['status'] }}">
                        {{ strtoupper($fee['status']) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right">Total:</td>
                <td class="text-right">₹{{ number_format($totalFees, 2) }}</td>
                <td class="text-right">₹{{ number_format($totalPaid, 2) }}</td>
                <td class="text-right">₹{{ number_format($totalPending, 2) }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Generated on {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }} | This is a computer-generated document
    </div>
</body>
</html>
