@extends('master')
@section('title', '| Bank To Bank Transfer')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Bank To Bank Tranfer</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title">Bank to Bank Transfer Table</h6>

                        <button class="btn btn-rounded-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#exampleModalLongScollable"><i data-feather="plus"></i></button>

                    </div>
                    <div id="" class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Image</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody class="showData">
                            </tbody>
                            {{-- <tr>
                                <td colspan="7" style="text-align: right;"><strong>Total Balance:</strong></td>
                                <td colspan="2" id="total-balance">0</td>
                            </tr> --}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Add Bank Modal -->
    <div class="modal fade" id="exampleModalLongScollable" tabindex="-1" aria-labelledby="exampleModalScrollableTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Bank To Bank Transfer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="save_bank_transfer_Form row" enctype=multipart/form-data>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">From <span class="text-danger">*</span></label>
                            <select name="from" class="form-control from" id="" onchange="errorRemove(this);"
                                onblur="errorRemove(this);">
                                <option value="" selected disabled>Select Bank From </option>
                                @foreach ($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                @endforeach

                            </select>
                            <span class="text-danger from_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">To <span class="text-danger">*</span> </label>
                            <select name="to" class="form-control to" id="" onchange="errorRemove(this);"
                                onblur="errorRemove(this);">
                                <option value="" selected disabled>Select Bank To </option>
                                @foreach ($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger to_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Amount <span class="text-danger">*</span></label>
                            <input id="defaultconfig" class="form-control amount" maxlength="39" name="amount"
                                type="number" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger amount_error"></span>
                        </div>
                        <div class=" col-md-6  ">
                            <label class=" bg-transparent"> Transfer Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control bg-transparent border-primary date"
                                onchange="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger date_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Description</label>
                            <textarea name="description" class="form-control" id="" cols="30" rows="5"></textarea>

                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Image</label>
                            <input id="defaultconfig" class="form-control account" name="image" type="file">
                            <span class="text-danger image_error"></span>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_bank_transfer">Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>




    <script>
        // error remove
        function errorRemove(element) {
            if (element.value != '') {
                $(element).siblings('span').hide();
                $(element).css('border-color', 'green');
            }
        }

        $(document).ready(function() {
            // Show error
            function showError(name, message) {
                $(name).css('border-color', 'red');
                $(name).focus();
                $(`${name}_error`).show().text(message);
            }

            // Save bank transfer
            const save_bank_transfer = document.querySelector('.save_bank_transfer');
            save_bank_transfer.addEventListener('click', function(e) {
                e.preventDefault();
                const fromBank = document.querySelector('.from').value;
                const toBank = document.querySelector('.to').value;

                // Check if the "From" and "To" banks are the same
                if (fromBank && toBank && fromBank === toBank) {
                    toastr.error('The "From" and "To" banks cannot be the same.');
                    showError('.from', 'The "From" and "To" banks cannot be the same.');
                    showError('.to', 'The "From" and "To" banks cannot be the same.');
                    return; // Stop further execution
                }
                let formData = new FormData($('.save_bank_transfer_Form')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/transfer/bank/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        console.log(res);
                        if (res.status === 200) {
                            $('#exampleModalLongScollable').modal('hide');
                            bankTransferView();
                            $('.save_bank_transfer_Form')[0].reset();
                            toastr.success(res.message);
                        } else if (res.status === 405) {
                            // if (res.error.from) showError('.from', res.error.from[0]);
                            // if (res.error.to) showError('.to', res.error.to[0]);
                            // if (res.error.amount) showError('.amount', res.error.amount[0]);
                            // if (res.error.date) showError('.date', res.error.date[0]);

                            toastr.error(res.errormessage); // Specific message

                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 500) {
                            toastr.error('Server error occurred. Please contact support.');
                            console.log('Server Error:', xhr.responseText);
                        } else if (xhr.status === 422) {
                            let errors = xhr.responseJSON.error;
                            if (errors.from) showError('.from', errors.from[0]);
                            if (errors.to) showError('.to', errors.to[0]);
                            if (errors.amount) showError('.amount', errors.amount[0]);
                            if (errors.date) showError('.date', errors.date[0]);
                        } else {
                            toastr.error('An error occurred. Please try again.');
                        }
                    }
                });
            });
        });




        function bankTransferView() {
            // console.log('hello');
            $.ajax({
                url: '/bank/transfer/view',
                method: 'GET',
                success: function(res) {
                    const banks = res.data;
                    // console.log(banks);
                    $('.showData').empty();
                    if (banks.length > 0) {
                        $.each(banks, function(index, bank) {
                            //  Calculate the sum of account_transaction balances
                            console.log(bank);
                            const imageHtml = bank.image ?
                                `<img src="/uploads/bank_transfer/${bank.image}" alt="Transfer Receipt" style="width: 50px; height: 50px; cursor: pointer;" onclick="showImage('/uploads/bank_transfer/${bank.image}')">` :
                                'No Image';
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                    <td>${index + 1}</td>
                                    <td>${bank.from_bank ? bank.from_bank.name : 'N/A'}</td> <!-- Access related bank name -->
                                   <td>${bank.to_bank ? bank.to_bank.name : 'N/A'}</td> <!-- Access related bank name -->
                                    <td>${bank.amount ?? 0}</td>
                                    <td>${bank.transfer_date ?? 0}</td>
                                      <td>${imageHtml}</td>
                                    <td>${bank?.description ?? 'N/A'}</td>

                                `;
                            $('.showData').append(tr);
                        });


                    } else {
                        $('.showData').html(`
                            <tr>
                                <td colspan='9'>
                                    <div class="text-center text-warning mb-2">Data Not Found</div>
                                    <div class="text-center">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalLongScollable">Add Bank Info<i data-feather="plus"></i></button>
                                    </div>
                                </td>
                            </tr>
                            `);
                    }
                }
            });
        }
        bankTransferView();
    </script>
@endsection
