// JS 必須先引用此套件<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
// HTML 使用範例:  
//<input type="text" name="company_name" data-alarm-name='這邊要輸入欄位的中文名稱' onfocus="this.style.borderColor=''" required class="form-control">
// JS 調用範例

window.ViewBox=class ViewBox {
    constructor(container_head_id, head_json = null, container_file_id, file_json = null, container_detail_id, detail_json, modal_type = 'default', api_token = null, csrf_token = null, complete_btn_id) {
        this.container_head_id = container_head_id;
        this.head_json = head_json;
        this.container_file_id = container_file_id;
        this.file_json = file_json;
        this.container_id = container_detail_id;
        this.detail_json = detail_json;
        this.modal_type = modal_type;
        this.my_instance_name = null;
        this.item_index = 0;
        this.api_token = api_token;
        this.csrf_token = csrf_token;
        this.complete_btn_id = complete_btn_id;
    }
    /* 
        功能名稱: collect_instance_name
        傳入參數: name
        功能描述: 取得Instance 名稱，方便框架後續使用
        回傳: 無
    */
    set collect_instance_name(name) {
        this.my_instance_name = name;
        window.view_box_instance = name;
    }
    /* 
        功能名稱: export_form
        傳入參數: 無
        功能描述: 取得Form 表單序列化資料
        回傳: headForm.serialize()
    */
    get export_form() {
        return $('#headForm').serialize();
    }

    /* 
        功能名稱: export_file
        傳入參數: 無
        功能描述: 取得檔案
        回傳: DOM 
    */
    get export_file() {
        let file_dom = document.getElementById("file-5");
        //後端接收方式:  $files=$request->file("Files");
        return file_dom;
    }

    /* 
        功能名稱: export
        傳入參數: 無
        功能描述: 倒出表身資料
        回傳: export_data
    */
    get export() {
        let export_data = [];
        let export_check = true;
        $(`#${view_box_instance.container_id} tbody tr`).each(function (index, value) {
            let temp_object = {};
            $(this).find("td").each(function () {
                if ($(this)[0].childElementCount > 0 && $(this).attr("data-column_name") != undefined) {
                    alert('表單在編輯狀態不能匯出資料!')
                    export_check = false;
                    return false;
                }
                if ($(this).attr("data-column_name") != undefined) {
                    let column_name = $(this).attr("data-column_name");
                    if (column_name != '' && column_name != null && column_name != undefined) {
                        temp_object[column_name] = $(this).html().trim();
                    }
                }
            });
            if (export_check == false) {
                export_data = false;
                return false;
            }
            export_data.push(temp_object);
        });
        return export_data;
    }


    /* 
        功能名稱: _render_head
        傳入參數: 無
        功能描述: 渲染表頭
        回傳: 無
    */
    _render_head() {
        let columns = '';
        for (let a = 0; a < this.head_json.length; a++) {
            columns += `
            <div class="input-group mt-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">${this.head_json[a].title}</span>
                </div>`;


            switch (this.head_json[a].editContent.dom_element) {
                case 'input':
                    columns += ` <input type="${this.head_json[a].dom_element_type}" placeholder="${this.head_json[a].editContent.placeholder}" style="${this.head_json[a].editContent.style}" ${(this.head_json[a].editContent.readonly)?'readonly':''} ${this.head_json[a].editContent.custom_attribute} onkeyup="this.style.borderColor=''" data-column_name="${this.head_json[a].column_name}" id="${this.head_json[a].column_name}" name="${this.head_json[a].column_name}" class="${this.head_json[a].editContent.class} ${this.head_json[a].column_name}"></input>`;
                    break;
                case 'select':
                    let option_string = '';
                    if (this.head_json[a].editContent.select_ajax.status == true) {

                        let ajax_request = ViewBox._syn_post(this.head_json[a].editContent.select_ajax.url, this.head_json[a].editContent.select_ajax.parameter, this.api_token, this.csrf_token);
                        option_string += `<option value="choose">請選擇</option>`;
                        for (let c = 0; c < ajax_request.length; c++) {
                            option_string += `<option value="${ajax_request[c][this.head_json[a].editContent.select_ajax.select_value_name]}">${ajax_request[0][this.head_json[a].editContent.select_ajax.select_option_name]}</option>`;
                        }

                        columns += `<select class="${this.head_json[a].editContent.class} ${this.head_json[a].column_name}" style="${this.head_json[a].editContent.style}" ${this.head_json[a].editContent.custom_attribute}  data-column_name="${this.head_json[a].column_name}" id="${this.head_json[a].column_name}">
                                       ${option_string}
                                   </select>`;
                    } else {
                        columns += `<select class="${this.head_json[a].editContent.class} ${this.head_json[a].column_name}" style="${this.head_json[a].editContent.style}" data-column_name="${this.head_json[a].column_name}" name="${this.head_json[a].column_name}" id="${this.head_json[a].column_name}">
                                         ${this.head_json[a].editContent.select_fix_option}
                                    </select>`;
                    }


                    break;
            }
        }
        columns += `</div>`;
        $(`#${this.container_head_id}`).append(`
            <form id="headForm" class=" px-3" style="width:100%;">
                <div class="row">
                    ${columns}
                </div>
            </form>
        `);

        // 這邊需要Event 綁定
        for (let a = 0; a < this.head_json.length; a++) {
            if (this.head_json[a].editContent.event != undefined && this.head_json[a].editContent.event != null && this.head_json[a].editContent.event.length != 0) {
                for (let d = 0; d < this.head_json[a].editContent.event.length; d++) {
                    $(`#${this.head_json[a].column_name}`).bind(`${this.head_json[a].editContent.event[d].name}`, this.head_json[a].editContent.event[d].function_script);
                }
                var eventList = ["change", "keyup"];
                for (event of eventList) {
                    document.addEventListener(event, function (e) {
                        e.stopImmediatePropagation();
                        if (e.target.className.indexOf('column_C') > 0) {
                            let row_id = e.target.id.split('_').pop();
                            $(`#column_D_${row_id}`).val(parseInt(e.target.value) * 3);
                        }
                    });
                }
            }
        }

        $('select').selectpicker('refresh');
    }

    /* 
        功能名稱: _render_file
        傳入參數: 無
        功能描述: 渲染Input File
        回傳: 無
    */
    _render_file() {
        $(`#${this.container_file_id}`).append(`
            <div class="form-group" style="width:100%;">
              <div class="file-loading">
                  <input id="file-5"  type="file" ${(this.file_json.multiple==true)?'multiple':''} data-preview-file-type="${this.file_json.data_preview_file_type}" data-upload-url="${this.file_json.data_upload_url}" data-="${this.file_json.data_theme}">
              </div>
            </div>
    `);
        $('#file-5').fileinput({
            theme: 'fas',
            language: 'zh-TW',
            uploadAsync: false,
            uploadUrl: 'http://schedule_demo.com/file_upload',
            'showUpload': false
        });
    }

    /* 
        功能名稱: _render_detail_table
        傳入參數: 無
        功能描述: 渲染表身
        回傳: 無
    */
    _render_detail_table() {
        $(`#${this.container_id}`).append(`
            <div class="row">
             <div class="col-6"><button type="button" class="btn btn-info btn-lg mt-3 mb-3" id="${this.detail_json.detail_btn_id}" style="width: 100%;">新增明細</button></div>
             <div class="col-6"><button type="button" class="btn btn-success btn-lg mt-3 mb-3" id="${this.complete_btn_id}" style="width: 100%;">完成</button></div>
            </div>
        `);
        $(`#${this.container_id}`).append(`<table class="table table-hover"><thead><tr></tr></thead><tbody></tbody></table>`);
        $(`#${this.container_id} thead tr`).append(`<th>項次</th>`);
        for (let i = 0; i < this.detail_json.columns.length; i++) {
            $(`#${this.container_id} thead tr`).append(`<th>${this.detail_json.columns[i].title}</th>`);
        }
        $(`#${this.container_id} thead tr`).append(`<th>編輯</th>`);
        $('tbody').sortable({
            helper: function (e, ui) {
                ui.children().each(function () {
                    $(this).width($(this).width());
                });
                return ui;
            },
            stop: function (e, ui) {
                $(`#${view_box_instance.container_id} tbody tr`).each(function (index, value) {
                    $(this).find("td").eq(0).html(index + 1);
                });
            }
        }).disableSelection();
    }

    /* 
        功能名稱: _add_detail
        傳入參數: 無
        功能描述: 新增表身
        回傳: 無
    */
    _add_detail() {
        let get_specific_column = view_box_instance.export;


        let row_id;
        let columns = '';
        let item_num = ($(`#${this.container_id } tbody`).find('tr').length) + 1;
        row_id = ++this.item_index;
        columns += `<td id="item_num_text_${row_id}">${item_num}</td>`;
        this.detail_json.columns.forEach(data => {

            if (data.editContent.status == true) {
                columns += `<td  data-column_name="${(data.export)?data.column_name:''}" id="${data.column_name}_${row_id}_text">`;
                switch (data.editContent.dom_element) {
                    case 'input':
                        columns += ` <input type="${data.editContent.dom_element_type}" placeholder="${data.editContent.placeholder}" style="${data.editContent.style}" ${(data.editContent.readonly)?'readonly':''} ${data.editContent.custom_attribute} data-column_name="${data.column_name}" id="${data.column_name}_${row_id}" class="${data.editContent.class} ${data.column_name}"></input>`;
                        columns += `</td>`
                        break;
                    case 'select':
                        let option_string = '';
                        if (data.editContent.select_ajax.status == true) {
                            if (data.unique == true) {
                                get_specific_column = get_specific_column.map((a) => {
                                    return a[data.column_name];
                                })

                            }
                            let ajax_request = ViewBox._syn_post(data.editContent.select_ajax.url, data.editContent.select_ajax.parameter, this.api_token, this.csrf_token);
                            option_string += `<option value="choose">請選擇</option>`;
                            ajax_request.forEach(function (obj) {

                                if (data.unique == true) {
                                    if (!get_specific_column.includes(String(obj[data.editContent.select_ajax.select_value_name]))) {
                                        option_string += `<option value="${obj[data.editContent.select_ajax.select_value_name]}">${obj[data.editContent.select_ajax.select_option_name]}</option>`;
                                    }
                                } else {

                                    option_string += `<option value="${obj[data.editContent.select_ajax.select_value_name]}">${obj[data.editContent.select_ajax.select_option_name]}</option>`;
                                }
                            });
                            columns += `<select class="${data.editContent.class} ${data.column_name}" style="${data.editContent.style}" ${data.editContent.custom_attribute}  data-column_name="${data.column_name}" id="${data.column_name}_${row_id}">
                                           ${option_string}
                                       </select></td>`;
                        } else {
                            columns += `<select class="${data.editContent.class} ${data.column_name}" style="${data.editContent.style}" data-column_name="${data.column_name}" id="${data.column_name}_${row_id}">
                                             ${data.editContent.select_fix_option}
                                        </select></td>`;
                        }


                        break;
                }
                // columns+=`</td>`
            }
            // if(data.button==true){
            //     columns+=`<td>`;
            //     if(data.confirmButton != undefined && data.confirmButton !=""){columns+=`<button type="button" class="btn btn-md btn-success" style="color:white;" id="confirm_btn_${row_id}" onclick="ViewBox._confirm_btn('${row_id}');">確認</button>`};
            //     if(data.cancelButton != undefined && data.cancelButton !=""){columns+=`<button type="button" class="btn btn-md btn-secondary" style="color:white; display:none;" id="cancel_btn_${row_id}" onclick="ViewBox._cancel_btn('${row_id}');">取消</button>`};
            //     if(data.editButton != undefined && data.editButton !=""){columns+=`<button type="button" class="btn btn-md btn-warning" style="color:white; display:none;" id="edit_btn_${row_id}" onclick="ViewBox._edit_btn('${row_id}');">修改</button>`};
            //     if(data.deleteButton != undefined && data.deleteButton !=""){columns+=`<button type="button" class="btn btn-md btn-danger" style="color:white; " id="delete_btn_${row_id}" onclick="ViewBox._delete_btn('${row_id}');">刪除</button>`};
            //     columns+=`</td>`;
            // }
        });
        columns += `<td>
        <button type="button" class="btn btn-md btn-success" style="color:white;" id="confirm_btn_${row_id}" onclick="ViewBox._confirm_btn('${row_id}');">確認</button>
        <button type="button" class="btn btn-md btn-secondary" style="color:white; display:none;" id="cancel_btn_${row_id}" onclick="ViewBox._cancel_btn('${row_id}');">取消</button>
        <button type="button" class="btn btn-md btn-warning" style="color:white; display:none;" id="edit_btn_${row_id}" onclick="ViewBox._edit_btn('${row_id}');">修改</button>
        <button type="button" class="btn btn-md btn-danger" style="color:white; " id="delete_btn_${row_id}" onclick="ViewBox._delete_btn('${row_id}');">刪除</button>
        </td>`;

        $(`#${this.container_id} tbody`).append(`<tr id="${row_id}">
                ${columns}
        </tr>`);
        $('select').selectpicker('refresh');
    }


    /* 
        功能名稱: _header_element_require
        傳入參數: _this(DOM)
        功能描述: 驗證必填欄位
        回傳: true / false
    */
    _header_element_require(_this) {
        if (_this.value == '' || _this.value == null || _this.value == 'choose') {
            let message = $(_this).attr("data-alarm-name") + ' 不可為空值';
            this._popup_message_with_type(this.modal_type, message);
            _this.focus();
            _this.value = '';
            $(_this).css('border-color', 'red');
            return false;
        } else {
            return true;
        }
    }
    /* 
        功能名稱: _header_element_email
        傳入參數: _this(DOM)
        功能描述: 驗證Email 欄位
        回傳: true / false
    */
    _header_element_email(_this) {
        if (!/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(_this.value)) {
            let message = $(_this).attr("data-alarm-name") + ' 格式錯誤';
            this._popup_message_with_type(this.modal_type, message);
            _this.focus();
            _this.value = '';
            $(_this).css('border-color', 'red');
            return false;
        } else {
            return true;
        }
    }

    /* 
        功能名稱: _header_element_max
        傳入參數: _this(DOM)
        功能描述: 驗證最大值欄位
        回傳: true / false
    */
    _header_element_max(_this, max) {
        if (parseFloat(_this.value) > max) {
            let message = $(_this).attr("data-alarm-name") + ' 大於上限值' + max;
            this._popup_message_with_type(this.modal_type, message);
            _this.focus();
            _this.value = '';
            $(_this).css('border-color', 'red');
            return false;
        } else {
            return true;
        }
    }

    /* 
        功能名稱: _header_element_min
        傳入參數: _this(DOM)
        功能描述: 驗證最大值欄位
        回傳: true / false
    */
    _header_element_min(_this, min) {
        if (parseFloat(_this.value) < min) {
            let message = $(_this).attr("data-alarm-name") + ' 低於下限值' + min;
            this._popup_message_with_type(this.modal_type, message);
            _this.focus();
            _this.value = '';
            $(_this).css('border-color', 'red');
            return false;
        } else {
            return true;
        }
    }

    /* 
        功能名稱: _validate
        傳入參數: 無
        功能描述: 開始驗證程序，可針對Form表單或個別DOM 去做驗證
        回傳: true / false
    */
    _validate(document_id) {
        let validate_status = true;
        if ($(`#${document_id}`)[0].tagName == 'FORM' && $(`#${document_id}`)[0] instanceof HTMLElement) {
            // 若驗證的DOM 是Form 由下列程式來做檢查
            $(`#${document_id}`).find('input,select').each((i, e) => {
                if ($(e).prop('required')) {
                    let pass_or_fail = this._header_element_require(e);
                    if (!pass_or_fail) {
                        validate_status = false;
                        return false;
                    }


                    if (e.type == 'email' && e.value != '') {
                        let pass_or_fail = this._header_element_email(e);
                        if (!pass_or_fail) {
                            validate_status = false;
                            return false;
                        }
                    }
                    if ($(e).prop('max')) {
                        let pass_or_fail = this._header_element_max(e, $(e).prop('max'));
                        if (!pass_or_fail) {
                            validate_status = false;
                            return false;
                        }
                    }
                    if ($(e).prop('min')) {
                        let pass_or_fail = this._header_element_min(e, $(e).prop('min'));
                        if (!pass_or_fail) {
                            validate_status = false;
                            return false;
                        }
                    }
                }

            });
        } else if ($(`#${document_id}`)[0].tagName != 'FORM' && $(`#${document_id}`)[0] instanceof HTMLElement) {
            // 若驗證的DOM 是Input 由下列程式來做檢查
            if ($(`#${culumn_id}`).prop('required')) {
                let pass_or_fail = this._header_element_require(e);
                if (!pass_or_fail) {
                    validate_status = false;
                    return false;
                }
            }
            if (this.type == 'email' && this.value != '') {
                let pass_or_fail = this._header_element_email(e);
                if (!pass_or_fail) {
                    validate_status = false;
                    return false;
                }
            }
        }
        return validate_status;
    }


    /* 
        功能名稱: _complete_validate
        傳入參數: 無
        功能描述: 驗證完表頭和表身
        回傳: true / false
    */
    _complete_validate() {
        if (this.my_instance_name._validate('headForm') == false) {
            return false;
        }
        if (this.my_instance_name.export.length == 0) {
            this._popup_message_with_type(this.modal_type, '表身不可為空值');
            return false;
        }
        return true;
    }

    /* 
        功能名稱: _confirm_btn
        傳入參數: row_id
        功能描述: Table 新增一列後按下確認按鈕
        回傳: 無
    */
    static _confirm_btn(row_id) {
        let json_data = {};
        $(`#${row_id}`).find('td').each(function () {
            //必須驗證才行
            if ($(this).find('select,input').length > 0) {
                let get_value = $(this).find('select,input').val();
                let get_column_name = $(this).find('select,input').attr("data-column_name");
                json_data[get_column_name] = get_value;
            }
        })
        ///////////////////

        let detail_json = view_box_instance.detail_json;
        let check_invalid_status = true;
        let column_title = '';
        //驗證
        for (let a = 0; a < detail_json.columns.length; a++) {
            column_title = detail_json.columns[a].title
            //必填欄位檢查
            if (detail_json.columns[a].editContent.status == true && detail_json.columns[a].editContent.validate.nullable == false) {
                check_invalid_status = view_box_instance._element_require(column_title, json_data[detail_json.columns[a].column_name]);
                if (!check_invalid_status) {
                    return;
                }
            }
            //Email格式檢查
            if (detail_json.columns[a].editContent.status == true && detail_json.columns[a].editContent.validate.nullable == false && detail_json.columns[a].editContent.dom_element_type == 'email') {
                check_invalid_status = view_box_instance._element_email(column_title, json_data[detail_json.columns[a].column_name]);
                if (!check_invalid_status) {
                    $(`#${detail_json.columns[a].column_name}_${row_id}`).val('');
                    return;
                }
            }
            //數字區間檢查
            if (detail_json.columns[a].editContent.status == true && detail_json.columns[a].editContent.validate.nullable == false && detail_json.columns[a].editContent.dom_element_type == 'number') {
                check_invalid_status = view_box_instance._element_number_range(column_title, json_data[detail_json.columns[a].column_name], detail_json.columns[a].editContent.validate.min, detail_json.columns[a].editContent.validate.max);
                if (!check_invalid_status) {
                    $(`#${detail_json.columns[a].column_name}_${row_id}`).val('');
                    return;
                }
            }
        }
        if (check_invalid_status == false) {
            alert(column_title + '欄位不可為空值!');
            return;
        }

        $(`#${row_id}`).find('td').each(function () {
            if ($(this).find('select,input').length > 0) {
                let get_value = $(this).find('select,input').val()
                $(this).find('select,input').remove();
                this.innerText = get_value;
            }
        });
        $(`#confirm_btn_${row_id}`).css('display', 'none');
        $(`#cancel_btn_${row_id}`).css('display', 'none');
        $(`#edit_btn_${row_id}`).css('display', '');
        $(`#delete_btn_${row_id}`).css('display', '');

        ///////////////////
        // let validate_response=validate_detail(json_data);
        // if(validate_response){
        //     $(`#${row_id}`).find('td').each(function(){
        //         if($(this).find('select,input').length>0){
        //             let get_value=$(this).find('select,input').val()
        //             $(this).find('select,input').remove();
        //             this.innerText=get_value;
        //         }
        //     });
        //     $(`#confirm_btn_${row_id}`).css('display','none');
        //     $(`#cancel_btn_${row_id}`).css('display','none');
        //     $(`#edit_btn_${row_id}`).css('display','');
        //     $(`#delete_btn_${row_id}`).css('display','');
        // }
    }

    /* 
        功能名稱: _edit_btn
        傳入參數: row_id
        功能描述: Table 在某列按下修改按鈕
        回傳: 無
    */
    static _edit_btn(row_id) {
        let get_specific_column = view_box_instance.export;
        let detail_json = view_box_instance.detail_json;
        let json_data = {};

        detail_json.columns.forEach(data => {
            if (data.column_name != undefined && data.column_name != null && data.column_name != '') {
                json_data[`${data.column_name}_${row_id}`] = $(`#${data.column_name}_${row_id}_text`).text();
            }
        });


        ///////////////////////

        detail_json.columns.forEach(data => {

            if (data.editContent.status == true) {
                switch (data.editContent.dom_element) {
                    case 'input':
                        $(`#${data.column_name}_${row_id}_text`).append(`<input type="${data.editContent.dom_element_type}"  placeholder="${data.editContent.placeholder}" ${(data.editContent.readonly)?'readonly':''} ${data.editContent.custom_attribute} data-column_name="${data.column_name}" id="${data.column_name}_${row_id}" class="${data.editContent.class} ${data.column_name}" value="${json_data[`${data.column_name}_${row_id}`]}"></input>`);
                        break;
                    case 'select':
                        let option_string = '';
                        if (data.editContent.select_ajax.status == true) {
                            if (data.unique == true) {
                                get_specific_column = get_specific_column.map((a) => {
                                    return a[data.column_name];
                                })
                            }
                            let ajax_request = ViewBox._syn_post(data.editContent.select_ajax.url, data.editContent.select_ajax.parameter, view_box_instance.api_token, view_box_instance.csrf_token);
                            option_string += `<option value="choose">請選擇</option>`;
                            ajax_request.forEach(function (obj) {

                                if (data.unique == true) {
                                    if (get_specific_column.includes(String(obj[data.editContent.select_ajax.select_value_name])) == false) {
                                        option_string += `<option value="${obj[data.editContent.select_ajax.select_value_name]}" ${(json_data[`${data.column_name}_${row_id}`]==obj[data.editContent.select_ajax.select_value_name])?'selected':''}>${obj[data.editContent.select_ajax.select_option_name]}</option>`;
                                    } else if (get_specific_column.includes(String(obj[data.editContent.select_ajax.select_value_name])) == true && String(obj[data.editContent.select_ajax.select_value_name]) == $(`#${data.column_name}_${row_id}_text`).text().trim()) {
                                        option_string += `<option value="${obj[data.editContent.select_ajax.select_value_name]}" ${(json_data[`${data.column_name}_${row_id}`]==obj[data.editContent.select_ajax.select_value_name])?'selected':''}>${obj[data.editContent.select_ajax.select_option_name]}</option>`;
                                    }
                                } else {

                                    option_string += `<option value="${obj[data.editContent.select_ajax.select_value_name].trim()}" ${(json_data[`${data.column_name}_${row_id}`].trim()==obj[data.editContent.select_ajax.select_value_name].trim())?'selected':''}>${obj[data.editContent.select_ajax.select_option_name].trim()}</option>`;
                                }

                                // option_string+=`<option value="${obj[data.editContent.select_ajax.select_value_name]}" ${(json_data[`${data.column_name}_${row_id}`]==obj[data.editContent.select_ajax.select_value_name])?'selected':''}>${obj[data.editContent.select_ajax.select_option_name]}</option>`;
                            });
                            $(`#${data.column_name}_${row_id}_text`).append(`
                                <select class="${data.editContent.class} ${data.column_name}"  data-column_name="${data.column_name}" id="${data.column_name}_${row_id}">
                                    ${option_string}
                                </select>
                            `);

                        } else {
                            $(`#${data.column_name}_${row_id}_text`).append(`
                                        <select class="${data.editContent.class} ${data.column_name}"  data-column_name="${data.column_name}" id="${data.column_name}_${row_id}">
                                             ${data.editContent.select_fix_option}
                                        </select>`);
                        }
                        break;
                }
                // columns+=`</td>`
            }
            // if(data.button==true){
            //     if(data.confirmButton != undefined && data.confirmButton !=""){$(`#confirm_btn_${row_id}`).css('display','');};
            //     if(data.cancelButton != undefined && data.cancelButton !=""){$(`#cancel_btn_${row_id}`).css('display','');};
            //     if(data.editButton != undefined && data.editButton !=""){$(`#edit_btn_${row_id}`).css('display','none');};      
            //     if(data.deleteButton != undefined && data.deleteButton !=""){$(`#delete_btn_${row_id}`).css('display','none');};
            // }
            $(`#confirm_btn_${row_id}`).css('display', '');
            $(`#cancel_btn_${row_id}`).css('display', '');
            $(`#edit_btn_${row_id}`).css('display', 'none');
            $(`#delete_btn_${row_id}`).css('display', 'none');
        });
    }

    /* 
        功能名稱: _cancel_btn
        傳入參數: row_id
        功能描述: Table 在某列編輯狀態下，按下取修鈕
        回傳: 無
    */
    static _cancel_btn(row_id) {
        $(`#${row_id}`).find('td').each(function () {
            if ($(this).find('select,input').length > 0) {
                $(this).find('select,input').remove();
            }
        });
        $(`#confirm_btn_${row_id}`).css('display', 'none');
        $(`#cancel_btn_${row_id}`).css('display', 'none');
        $(`#edit_btn_${row_id}`).css('display', '');
        $(`#delete_btn_${row_id}`).css('display', '');
    }
    /* 
        功能名稱: _delete_btn
        傳入參數: row_id
        功能描述: Table 在某列，按下取刪除按
        回傳: 無
    */
    static _delete_btn(row_id) {
        //刪除
        $(`#${row_id}`).remove();
        $(`#${view_box_instance.container_id} tbody tr`).each(function (index, value) {
            $(this).find("td").eq(0).html(index + 1);
        });
    }

    /* 
        功能名稱: _syn_post
        傳入參數: url,parameter=null, api_token, csrf_token
        功能描述: 同步AJAX API 調用
        回傳: 無
    */
    static _syn_post(url, parameter = null, api_token = null, csrf_token = null) {
        var request = new XMLHttpRequest();
        request.open('POST', url, false); // `false` makes the request synchronous
        request.setRequestHeader("Authorization", api_token);
        request.setRequestHeader("X-CSRF-TOKEN", csrf_token);
        request.setRequestHeader("Content-Type", "application/json");

        request.send((typeof parameter === 'function') ? JSON.stringify(parameter()) : parameter);

        if (request.status === 200) {
            return JSON.parse(request.responseText);
        }
        if (request.status === 500) {
            alert(request.responseText);
            return false;
        }
    }
    /* 
        功能名稱: _asyn_post
        傳入參數: url,parameter=null, api_token, csrf_token
        功能描述: 非同步AJAX API 調用
        回傳: 無
    */
    static _asyn_post(url, parameter = null, api_token = null, csrf_token = null) {
        return new Promise((resolve, reject) => {
            // 定義 Http request
            var req = new XMLHttpRequest();
            req.open('POST', url);
            request.setRequestHeader("Authorization", api_token);
            request.setRequestHeader("X-CSRF-TOKEN", csrf_token);
            request.setRequestHeader("Content-Type", "application/json");
            req.onload = function () {
                if (req.status == 200) {
                    // 使用 resolve 回傳成功的結果，也可以在此直接轉換成 JSON 格式
                    resolve(JSON.parse(req.response));
                } else {
                    // 使用 reject 自訂失敗的結果
                    reject(new Error(req))
                }
            };
            req.send((typeof parameter === 'function') ? JSON.stringify(parameter()) : parameter);
        });
    }
    /* 
        功能名稱: _popup_message_pretty
        傳入參數: message
        功能描述: 顯示彈跳視窗
        回傳: 無
    */
    _popup_message_pretty(message) {
        Swal.fire({
            icon: 'warning',
            title: 'Oops...',
            text: message,
            timer: 2000,
            showCancelButton: false,
            showConfirmButton: false
        });
    }

    /* 
        功能名稱: _element_require
        傳入參數: column_title,value
        功能描述: 檢查欄位是否必填
        回傳: true / false
    */
    _element_require(column_title, value) {
        if (value == '' || value == null || value == 'choose') {
            let message = column_title + ': 不可為空值';
            this._popup_message_with_type(this.modal_type, message);
            return false;
        } else {
            return true;
        }
    }
    /* 
        功能名稱: _element_number_range
        傳入參數: column_title,value,min,ma
        功能描述: 驗證數值區間
        回傳: true / false
    */
    _element_number_range(column_title, value, min, max) {
        min = parseFloat(min);
        max = parseFloat(max);
        value = parseFloat(value);
        if (min != null && min != undefined && value < min) {
            let message = `${column_title}: 低於${min}`;
            this._popup_message_with_type(this.modal_type, message);
            return false;
        } else if (max != null && max != undefined && value > max) {
            let message = `${column_title}: 大於${max}`;
            this._popup_message_with_type(this.modal_type, message);
            return false;
        } else {
            return true;
        }
    }
    /* 
        功能名稱: _element_email
        傳入參數: column_title,value
        功能描述: 驗證Email 欄位
        回傳: true / false
    */
    _element_email(column_title, value) {
        if (!/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(value)) {
            let message = column_title + ': Email 格式錯誤';
            this._popup_message_with_type(this.modal_type, message);
            return false;
        } else {
            return true;
        }
    }
    /* 
        功能名稱: _popup_message_with_type
        傳入參數: type,message
        功能描述: 檢查要用哪一種彈跳視窗種類，然後各自調用
        回傳: 無
    */
    _popup_message_with_type(type, message) {
        switch (type) {
            case 'pretty':
                this._popup_message_pretty(message);
                break;
            default:
                this._popup_message_default(message);
                break;
        }
    }
    /* 
        功能名稱: _popup_message_default
        傳入參數: message
        功能描述: 透過預設彈跳視窗來顯示訊息
        回傳: 無
    */
    _popup_message_default(message) {
        alert(message);
    }
    /* 
        功能名稱: _popup_message_pretty
        傳入參數: message
        功能描述: 透過Sweat alert彈跳視窗來顯示訊息
        回傳: 無
    */
    _popup_message_pretty(message) {
        Swal.fire({
            icon: 'warning',
            title: 'Oops...',
            text: message,
            timer: 2000,
            showCancelButton: false,
            showConfirmButton: false
        });
    }


}
