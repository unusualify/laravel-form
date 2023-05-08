@once
    @php
        $spreadSheetTableId = 'spreadSheetTable';
    @endphp
@endonce
@php
    $spreadsheet_id =  "spreadsheetID_" . mt_rand(100000,999999);
    $value = $model->getFormInputValue( $input_name ) ?? [];
    $click_label = "Add file";
    if(count($value) > 0){
        $click_label = "Update file";
    }

    $table_name = $model->getTable();

@endphp

<label  class="form-label">
    {{$label}}
</label>
<div class="input-group mb-3">
    {{-- <div class="input-group-prepend">
      <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
    </div> --}}
    <div class="custom-file">
        <input
            type="hidden"
            name="{{ $input_name }}"
            value="{{ count($value) > 0 ? json_encode($value) : ''}}"
            >
        <input
            id="{{ $spreadsheet_id }}"
            accept=".xls,.xlsx,.csv"
            class="form-control"
            type="file"
            {{-- value="{{ $value }}" --}}
            {{$props ?? ''}}

            >
        <label
            class="custom-file-label" for="{{ $spreadsheet_id }}"
            data-toggle="tooltip" title="Please, upload one of .xls and .xlsx formats" data-original-title="Default tooltip"

            >
            {{ $click_label }}
        </label>
    </div>
    <button
        type="button" class="btn btn-success ml-2 spreadSheet"
        @if(count($value) < 1)
            style="display: none;"
        @endif
        data-toggle="tooltip" title="Show List" data-original-title="Default tooltip"
        >
        <i class="cil-cursor"></i>

    </button>
    <button
        type="button" class="btn btn-danger ml-2 spreadSheet-delete"
        @if(count($value) < 1)
            style="display: none;"
        @endif
        data-toggle="tooltip" title="Clear List" data-original-title="Default tooltip"
        >
        <i class="cil-x-circle"></i>
    </button>

    @if( isset($sample_file) && file_exists(  storage_path('app/samples/'.$sample_file) ))
        <a
            href="{{ route('sample.download', [$sample_file]) }}"
            class="btn btn-info ml-2 spreadSheet"
            target="_blank"
            data-toggle="tooltip" title="Sample File" data-original-title="Default tooltip">
            <i class="cil-arrow-thick-bottom"></i>
        </a>
    @endif
</div>
<!-- Large modal -->

@once

    <div class="modal modal-fullscreen fade bd-example-modal-lg" tabindex="-1" role="dialog" id="{{$spreadSheetTableId}}Modal" aria-labelledby="spreadSheetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="">List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="{{ $spreadSheetTableId }}" class="table">

                    </table>
                </div>

            </div>
        </div>
    </div>

    @push('postcss')

    @endpush
    @push('postscript')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>

        <script>
            function exportJSON(selector){
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls|.csv)$/;
                /*Checks whether the file is a valid excel file*/
                if (regex.test($(selector).val().toLowerCase())) {
                    var xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/
                    $flag = '';
                    if ($(selector).val().toLowerCase().indexOf(".xlsx") > 0) {
                        xlsxflag = true;
                        flag = 'xlsx';
                    }  else if ($(selector).val().toLowerCase().indexOf(".xls") > 0) {
                        flag = 'xls';
                    } else if ($(selector).val().toLowerCase().indexOf(".csv") > 0) {
                        flag = 'csv';
                    }
                    /*Checks whether the browser supports HTML5*/
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            var data = e.target.result;
                            /*Converts the excel data in to object*/
                            if (flag  == 'xlsx' ) {
                                var workbook = XLSX.read(data, { type: 'binary' });
                            } else if (flag == 'xls') {
                                var workbook = XLS.read(data, { type: 'binary' });
                            } else if (flag == 'csv') {
                                var workbook = data;
                            }

                            var exceljson = [];
                            if( flag == 'xlsx' || flag == 'xls'){
                                /*Gets all the sheetnames of excel in to a variable*/
                                var sheet_name_list = workbook.SheetNames;

                                var cnt = 0; /*This is used for restricting the script to consider only first sheet of excel*/
                                sheet_name_list.every(function (y) { /*Iterate through all sheets*/
                                    /*Convert the cell value to Json*/

                                    if (flag == 'xlsx') {
                                        exceljson= XLSX.utils.sheet_to_json(workbook.Sheets[y]);
                                    } else if (flag == 'xls') {
                                        exceljson = XLS.utils.sheet_to_row_object_array(workbook.Sheets[y]);
                                    }

                                    // if (exceljson.length > 0 && cnt == 0) {
                                    //     $(selector).siblings('input:hidden').val(JSON.stringify(exceljson));
                                    //     $(selector).parent().siblings('button.spreadSheet').first().fadeIn();
                                    //     // BindTable(exceljson, '#' + '{{ $spreadSheetTableId }}');
                                    //     cnt++;
                                    // }
                                    return false;

                                });
                            } else if( flag == 'csv'){
                                var rows = workbook.split(";;\r\n");
                                var columns = [];
                                for (let i in rows) {
                                    if(i == 0 ){
                                        columns = rows[i].split(";");
                                    }else{
                                        let cells = rows[i].split(";");

                                        if(cells.length == columns.length){
                                            let row = {};
                                            cells.forEach( (val,i) => {
                                                console.log(
                                                    columns[i].toLowerCase()
                                                )
                                                row[columns[i]] = val
                                            });
                                            exceljson.push(row)
                                        }
                                    }
                                    //loop each row split them by "," and save it in array
                                }
                            }
                            exceljson = exceljson.map((item) => {
                                return lowercaseKeys(item)
                            })

                            if (exceljson.length > 0) {
                                $(selector).siblings('input:hidden').val(JSON.stringify(exceljson));
                                $(selector).parent().siblings('button').fadeIn();
                            }
                            // $( '#' + '{{ $spreadSheetTableId }}').show();
                        }
                        if (xlsxflag) {/*If excel file is .xlsx extension than creates a Array Buffer from excel*/
                            reader.readAsArrayBuffer($(selector)[0].files[0]);
                        }
                        else {
                            reader.readAsBinaryString($(selector)[0].files[0]);
                        }
                    }
                    else {
                        alert("Sorry! Your browser does not support HTML5!");
                    }
                }
                else {
                    alert("Please upload a valid Excel file!");
                }
            }
            function bindTable(jsondata, tableid) {/*Function used to convert the JSON array to Html Table*/
                $(tableid).html('');
                var columns = bindTableHeader(jsondata, tableid); /*Gets all the column headings of Excel*/
                var tbody$ = $('<tbody/>');
                for (var i = 0; i < jsondata.length; i++) {

                    var rowClass = ""
                    if(columns.length > 2 && jsondata[i][columns[2]] == '1'){
                        rowClass = 'table-warning';
                    }
                    var row$ = $(`<tr class="${rowClass}"></tr>`);
                    row$.append($('<th scope="row"></th>').html(i+1))

                    for (var colIndex = 0; colIndex < columns.length; colIndex++) {
                        var cellValue = jsondata[i][columns[colIndex]];
                        if (cellValue == null)
                            cellValue = "";
                        row$.append($('<td/>').html(cellValue));
                    }
                    $(tbody$).append(row$);
                }
                $(tableid).append(tbody$);
            }
            function bindTableHeader(jsondata, tableid) {/*Function used to get all column names from JSON and bind the html table header*/
                var columnSet = [];
                var thead$ = $('<thead/>');
                var headerTr$ = $('<tr/>');
                headerTr$.append($('<th scope="col"></th>').html('No'));
                for (var i = 0; i < jsondata.length; i++) {
                    var rowHash = jsondata[i];
                    for (var key in rowHash) {
                        if (rowHash.hasOwnProperty(key)) {
                            if ($.inArray(key, columnSet) == -1) {/*Adding each unique column names to a variable array*/
                                columnSet.push(key);
                                headerTr$.append($('<th scope="col"></th>').html(key));
                            }
                        }
                    }
                    $(thead$).append(headerTr$);
                }
                $(tableid).append(thead$);
                return columnSet;
            }

            function lowercaseKeys(obj) {
                return Object.keys(obj).reduce((accumulator, key) => {
                    accumulator[key.toLowerCase()] = obj[key];
                    return accumulator;
                }, {});
            }

            $(document).ready(function(){
                $('button.spreadSheet').click(function(){
                    let modalId = '#' + '{{ $spreadSheetTableId }}' + 'Modal';
                    let json = $(this).siblings('div.custom-file').find('input:hidden').val();
                    let data = JSON.parse(json);

                    bindTable(data, '#' + '{{ $spreadSheetTableId }}');
                    $(modalId).modal('show');
                })
                $('button.spreadSheet-delete').click(function(){
                    $(this).siblings('div.custom-file').find('input:hidden').val('[]')
                    $(this)
                        .parent()
                        .children('button')
                        .fadeOut()
                })
            })

        </script>
    @endpush
@endonce

@push('componentscript')
    <script>
        $('#' + '{{ $spreadsheet_id }}').on('change',function(){
            //get the file name
            var fileName = $(this).val();
            $(this).next('.custom-file-label').html(fileName);
            if(fileName != ''){
                exportJSON( '#' + $(this).attr('id') );
            }else{
                $(this)
                    .parent()
                    .siblings('button')
                    .first()
                    .fadeOut()
            }
        })
    </script>
@endpush
