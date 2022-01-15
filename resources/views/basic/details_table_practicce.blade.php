<x-app-layout>
    <x-slot name="content">
        <div class="container-fluid">
            <div class="row mt-5 px-3">
                <div class="col-3">
                    <div id="yt_head_containter"></div>
                </div>
                <div class="col-9">
                    <div id="yt_file_containter"></div>

                </div>

                {{-- <div class="col-9">
                    <div class="mt-3" id="yt_containter" style="width:100%"></div>
                </div> --}}


            </div>
            <div class="row">
              <div class="col-12">
                <div class="mt-3" id="yt_containter" style="width:100%"></div>
              </div>
            </div>

        </div>



        {{-- !SECTION --}}
        {{-- SECTION 自定義js, css 載入區 --}}
    
        {{-- !SECTION --}}
        <script type="text/javascript">
            'use strict';

            var api_url = '{{ url('') }}';
            var viewBox = new ViewBox(
                'yt_head_containter',
                [{
                        "title": 'Header AA',
                        "column_name": 'head_column_aa',
                        "editContent": {
                            "status": true,
                            "dom_element": 'input',
                            "placeholder": '必填欄位',
                            "dom_element_type": 'number',
                            "custom_attribute": " min='1' max='15' required data-alarm-name='Header AA'",
                            "class": "form-control",
                            "style": "",
                            "readonly": false,
                            "event": [{
                                name: 'keyup',
                                function_script: (e) => {
                                    $('#head_column_b').val(e.target.value)
                                }
                            }]

                        },
                    },
                    // {
                    //     "title":'head_column_a',
                    //     "column_name":'head_column_a',                     
                    //     "editContent": {
                    //       'status':true,
                    //       'dom_element':'select',
                    //       'select_ajax':{
                    //         'status':true,
                    //         'url':`${api_url}/api/query_order_head_filter_with_month`,
                    //         'parameter':'',
                    //         'select_option_name':'omRoute',
                    //         'select_value_name':'id',
                    //       },
                    //       'select_fix_option':`
            //           <option value="A">A</option>
            //           <option value="B">B</option>
            //           <option value="C">C</option>`,
                    //       "class":"form-control selectpicker",
                    //       "style":"",
                    //       "custom_attribute":"data-live-search='true' required data-alarm-name='head_column_a'",
                    //       "readonly":false,
                    //       "event":[
                    //         {name:'change',function_script:(e)=>{
                    //             $('#head_column_b').val(e.target.value)
                    //           }
                    //         }]
                    //     },
                    // },
                    {
                        "title": 'Header B',
                        "column_name": 'head_column_b',
                        "editContent": {
                            "status": true,
                            "dom_element": 'input',
                            "placeholder": '必填欄位',
                            "dom_element_type": 'number',
                            "custom_attribute": " min='1' max='15' required data-alarm-name='Header B'",
                            "class": "form-control",
                            "style": "",
                            "readonly": true

                        },
                    },
                    {
                        "title": 'Header C',
                        "column_name": 'head_column_c',
                        "editContent": {
                            "status": true,
                            "dom_element": 'input',
                            "placeholder": '必填欄位',
                            "dom_element_type": 'number',
                            "custom_attribute": " min='1' max='15' required  data-alarm-name='Header C'",
                            "class": "form-control",
                            "style": "",
                            "readonly": false
                        },
                    }
                ],
                'yt_file_containter', {
                    multiple: true,
                    data_preview_file_type: 'any',
                    data_upload_url: '#',
                    data_theme: 'fas'
                },
                'yt_containter', {
                    "columns": [
                        // {
                        //     "title": '欄位B',
                        //     "column_name": 'column_b',
                        //     "export": true,
                        //     "unique": true,
                        //     "button": false,
                        //     "editContent": {
                        //         'status': true,
                        //         'dom_element': 'select',
                        //         'select_ajax': {
                        //             'status': true,
                        //             'url': `${api_url}/api/query_order_head_filter_with_month`,
                        //             'parameter': null, //()=>{ return {'month':document.getElementById("head_column_b").value}},
                        //             'select_option_name': 'omRoute',
                        //             'select_value_name': 'id'
                        //         },
                        //         'select_fix_option': `
                        //       <option value="A">A</option>
                        //       <option value="B">B</option>
                        //       <option value="C">C</option>`,
                        //         "class": "form-control  selectpicker",
                        //         "style": "",
                        //         "custom_attribute": "data-live-search='true'",
                        //         "readonly": false,
                        //         "validate": {
                        //             "nullable": false
                        //         }
                        //     },
                        // },
                        {
                            "title": '欄位C',
                            "column_name": 'column_C',
                            "export": true,
                            "unique": false,
                            "button": false,
                            "editContent": {
                                "status": true,
                                "dom_element": 'input',
                                "placeholder": '必填欄位',
                                "dom_element_type": 'number',
                                "custom_attribute": " min='1' max='15' required",
                                "class": "form-control",
                                "style": "",
                                "readonly": false,
                                "validate": {
                                    "nullable": false,
                                    "min": '1',
                                    "max": '50'
                                }

                            },
                        },
                        {
                            "title": '欄位D',
                            "column_name": 'column_D',
                            "export": true,
                            "unique": false,
                            "button": false,
                            "editContent": {
                                "status": true,
                                "dom_element": 'input',
                                "placeholder": '必填欄位',
                                "dom_element_type": 'number',
                                "custom_attribute": "maxlength='20'",
                                "class": "form-control",
                                "style": "",
                                "readonly": true,
                                "validate": {
                                    "nullable": false
                                }
                            },
                        },
                        {
                            "title": '欄位E',
                            "column_name": 'column_E',
                            "export": true,
                            "unique": false,
                            "button": false,
                            "editContent": {
                                "status": true,
                                "dom_element": 'input',
                                "placeholder": '必填欄位',
                                "dom_element_type": 'email',
                                "custom_attribute": "",
                                "class": "form-control",
                                "style": "",
                                "readonly": false,
                                "validate": {
                                    "nullable": false
                                }
                            },
                        },
                        // {
                        //   "title":'編輯',
                        //   "column_name":false,
                        //   "export":false,
                        //   "unique":false,
                        //   "button":true,
                        //   "editContent": false,
                        //   "confirmButton": '<button type="button" class="btn btn-md btn-success" style="color:white;">確認</button>',
                        //   "deleteButton": '<button type="button" class="btn btn-md btn-danger" style="color:white;">刪除</button>',
                        //   "cancelButton": '<button type="button" class="btn btn-md btn-secondary" style="color:white;">取消</button>',
                        //   "editButton": '<button type="button" class="btn btn-md btn-info" style="color:white;">修改</button>',
                        // }
                    ],
                    'detail_btn_id': 'add_detail_btn'
                },
                'default',
                'Bearer {{ Auth::user()->api_token }}',
                '{{ csrf_token() }}',
                'complete_btn'
            );
            viewBox.collect_instance_name = viewBox;


            window.addEventListener("load", async () => {
                viewBox._render_head();
                viewBox._render_file();
                viewBox._render_detail_table();


                $('#add_detail_btn').on('click', function() {
                    viewBox._add_detail();
                });
                $(document).on("change", 'select', function(e) {
                    e.stopImmediatePropagation();
                    if (e.target.className.indexOf('column_b') > 0) {
                        let row_id = e.target.id.split('_').pop();
                        $(`#column_C_${row_id}`).val(e.target.value);
                    }
                });

                $('#complete_btn').on('click', function() {
                    let status = viewBox._validate('headForm');
                    if (status == false) {
                        return;
                    }
                    let details = viewBox.export;
                    if (details == false) {
                        alert('明細不可為編輯狀態!');
                        return;
                    }
                    if (details.length == 0) {
                        alert('明細不可為空值!');
                        return;
                    }
                    let header = viewBox.export_form;

                    // 取得檔案
                    let Ajax_FormData=new FormData();
                    // 將檔案放置於FormData
                    let file_dom = viewBox.export_file;
                    for (const file of file_dom.files) {
                      Ajax_FormData.append("Files[]", file);
                    }

                    alert('成功');
                });

                var eventList = ["change", "keyup"];
                for (event of eventList) {
                    document.addEventListener(event, function(e) {
                        e.stopImmediatePropagation();
                        if (e.target.className.indexOf('column_C') > 0) {
                            let row_id = e.target.id.split('_').pop();
                            $(`#column_D_${row_id}`).val(parseInt(e.target.value) * 3);
                        }
                    });
                }
            });
        </script>

    </x-slot>
</x-app-layout>
