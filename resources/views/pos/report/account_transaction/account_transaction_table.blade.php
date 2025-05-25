<div class="row">
    <div class="col-md-12 ">
        <div id="" class="table-responsive">
            <table id="example" class="table w-100">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Payment Type</th>
                        <th>Branch Name</th>
                        <th>Purpose</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody class="showData">
                    @if ($accountTransaction->count() > 0)
                        @foreach ($accountTransaction as $key => $acountData)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $acountData['bank']['name'] ?? '' }}</td>
                                <td>{{ $acountData['branch']['name'] ?? '' }}</td>
                                {{-- <td>@if ($acountData->purpose == 'receive'){{'Deposit'}}@else{{'Withdrawal'}}@endif</td> --}}
                                <td>{{ $acountData->purpose ?? '' }}</td>
                                <td>{{ $acountData->debit ?? '0' }} TK</td>
                                <td>{{ $acountData->credit ?? '0' }}TK</td>
                                <td>{{ $acountData->balance ?? '0' }}TK</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="12">
                                <div class="text-center text-warning mb-2">Data Not Found</div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
