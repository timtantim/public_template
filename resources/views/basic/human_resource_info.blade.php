<x-app-layout>

    <x-slot name="content">
        <div class="container-fluid px-3">
            <div class="row">
                <a class="btn btn-danger btn-lg" style="width: 50%;" href="{{ url('/human_resource_info') }}"><i
                        class="fa fa-plus"></i>新增</a>
                <a class="btn btn-light btn-lg" style="width: 50%;" href="{{ url('/human_resource_search') }}"><i
                        class="fa fa-search"></i>查詢</a>
                {{-- <button type="button" class="btn btn-danger btn-lg" style="width: 50%;"><i class="fa fa-plus"></i>新增</button> --}}
                {{-- <button type="button" class="btn btn-light btn-lg"style="width: 50%;"><i class="fa fa-search"></i>查詢</button> --}}
            </div>

        </div>
        <div class="row mt-5 px-3">
            {{-- <form> --}}
            <div class="col-4">
                <form>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">帳號ID</span>
                        </div>
                        <input type="text" name="user_id" placeholder="必填欄位" maxlength="50" required id="user_id"
                            class="form-control">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">姓名</span>
                        </div>
                        <input type="text" name="name" placeholder="必填欄位" maxlength="50" required id="name"
                            class="form-control">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">電話</span>
                        </div>
                        <input type="text" name="phone" placeholder="必填欄位" maxlength="50" required id="phone"
                            class="form-control">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Email</span>
                        </div>
                        <input type="text" name="email" placeholder="必填欄位" maxlength="50" required id="email"
                            class="form-control">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">密碼</span>
                        </div>
                        <input type="password" name="password" placeholder="必填欄位" maxlength="50" required id="password"
                            class="form-control">
                    </div>
                    <button type="button" class="btn btn-success btn-lg" id="add_btn" style="width: 100%;">建立</button>
            </div>
            </form>
            <div class="col-8">
                <div id="shared-lists" class="input-group">
                    {{-- <h4 class="col-12">權限配置</h4> --}}
                    <div id="example2-left" class="list-group col">
                        <h4>待配置權限</h4>
                        {{-- <div class="list-group-item">Item 1</div> --}}
                    </div>


                    <div id="example2-right" class="list-group col">
                        <h4>已配置權限</h4>
                        {{-- <div class="list-group-item tinted">Item 1</div> --}}
                    </div>
                </div>
            </div>







            {{-- </form> --}}
        </div>
        {{-- !SECTION --}}
        {{-- SECTION 作廢主檔資料modal --}}
        <div id="modalDelete" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            </div>
        </div>
        {{-- !SECTION --}}
        {{-- SECTION 自定義js, css 載入區 --}}
        <script src="{{ asset('js/Sortable.js') }}" defer></script>
        <script src="{{ asset('js/prettify.js') }}" defer></script>
        <script src="{{ asset('js/run_prettify.js') }}" defer></script>
        <script src="{{ asset('js/list_drag_app.js') }}" defer></script>
        <script src="{{ asset('js/bootstrap-validate.js') }}" defer></script>
        {{-- !SECTION --}}
        <script type="text/javascript">
            let all_page = [];
            var api_url = '{{ url('') }}';
            let api_token = '{{ Auth::user()->api_token }}';
            let csrf_token= '{{ csrf_token() }}';

            function rander_drag_list() {
                new Sortable(example2Left, {
                    group: 'shared', // set both lists to same group
                    animation: 150
                });

                new Sortable(example2Right, {
                    group: 'shared',
                    animation: 150
                });
            }

            function load_all_page() {
                $.ajax({
                    type: 'POST',
                    // headers: {
                    //         "Authorization": 'Bearer {{ Auth::user()->api_token }}'
                    //     },
                    url: api_url + '/api/query_pages',
                    headers: {
                        "Authorization": `Bearer ${api_token}`,
                        "X-CSRF-TOKEN": csrf_token
                    },
                    success: function(data) {
                        for (let a = 0; a < data.length; a++) {
                            $(`#example2-left`).append(
                                `<div class="list-group-item tinted" data-select_page_id="${data[a].id}">${data[a].page_name}</div>`
                                )
                        }
                        rander_drag_list();
                    },
                    error: function(xhr, status, error) {
                        let err = xhr.responseText;
                        Swal.fire({
                            icon: 'error',
                            title: '',
                            text: err
                        });
                    }
                });
            }

            $(document).ready(function() {
                bootstrapValidate(['#email'], 'email:Email 格式有誤', function(isValid) {
                    if (isValid) {
                        $("#add_btn").prop("disabled", false);
                    } else {
                        $("#add_btn").prop("disabled", true);
                    }
                });
                load_all_page();
                $('#add_btn').on('click', function() {
                    let user_id = $('#user_id').val();
                    let name = $('#name').val();
                    let phone = $('#phone').val();
                    let email = $('#email').val();
                    let password = $('#password').val();

                    if (user_id == '' || name == '' || phone == '' || email == '' || password == '') {
                        Swal.fire({
                            icon: 'warning',
                            title: '',
                            text: '欄位必填'
                        });
                        return;
                    }
                    if (user_id.length > 50 || name.length > 50 || phone.length > 50 || email.length > 50 ||
                        password.length > 50) {
                        Swal.fire({
                            icon: 'warning',
                            title: '',
                            text: '欄位長度不得大於50字元'
                        });
                        return;
                    }

                    let select_page = [];
                    $("#example2-right div[data-select_page_id]").each(function() {
                        var testdata = $(this).data('select_page_id');
                        select_page.push(testdata);
                    });
                    if (select_page.length == 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: '',
                            text: '該人員尚未配置權限'
                        });
                        return;
                    }
                    $.ajax({
                        type: 'POST',
                        data: $('form').serialize() + '&page=' + select_page,
                        url: api_url + '/api/create_account_info',
                        success: function(data) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: '新增成功',
                                showConfirmButton: false
                            });
                            $('form').trigger("reset");
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            let err = xhr.responseText;
                            Swal.fire({
                                icon: 'error',
                                title: '',
                                text: err
                            });
                        }
                    });
                });


            });
        </script>

    </x-slot>
</x-app-layout>
