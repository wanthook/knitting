@extends('adminlte::page')

@section('title', 'Customer')

@section('content_header')
    <h1>Customer</h1>
@stop

@section('content')
<div class="modal fade" id="modal-form">
    <div class="modal-dialog">
        <div class="modal-content bg-secondary">
            <div class="modal-header">
                <h4 class="modal-title">Form Customer</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_data" action="{{route('customersave')}}" accept-charset="UTF-8" >
                {{csrf_field()}}
                <input type="hidden" name="id" id="id">
                <div class="modal-body">            
                    <div class="row">
                        <div class="col-12">                                
                            <div class="form-group">
                                <label for="kode">Customer</label>
                                <input type="text" class="form-control form-control-sm" id="kode" name="kode">
                            </div>
                            <div class="form-group">
                                <label for="nama1">Nama 1</label>
                                <input type="text" class="form-control form-control-sm" id="nama1" name="nama1">
                            </div>
                            <div class="form-group">
                                <label for="nama2">Nama 2</label>
                                <input type="text" class="form-control form-control-sm" id="nama2" name="nama2">
                            </div>
                            <div class="form-group">
                                <label for="kota">Kota</label>
                                <input type="text" class="form-control form-control-sm" id="kota" name="kota">
                            </div>
                            <div class="form-group">
                                <label for="jalan1">Jalan 1</label>
                                <input type="text" class="form-control form-control-sm" id="jalan1" name="jalan1">
                            </div>
                            <div class="form-group">
                                <label for="jalan2">Jalan 2</label>
                                <input type="text" class="form-control form-control-sm" id="jalan2" name="jalan2">
                            </div>
                            <div class="form-group">
                                <label for="country_id">Country</label>
                                <select name="country_id" id="country_id" class="form-control form-control-sm select2" style="width:100%"></select>
                            </div>
                            <div class="form-group">
                                <label for="group_id">Group</label>
                                <select name="group_id" id="group_id" class="form-control form-control-sm select2" style="width:100%"></select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" id="cmdModalClose" class="btn btn-outline-light" data-dismiss="modal">Keluar</button>
                    <button type="submit" id="cmdModalSave" class="btn btn-outline-light">Simpan</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-form-upload">
    <div class="modal-dialog">
        <div class="modal-content bg-secondary">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-upload"></i>Form Upload Customer</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_data_upload" action="{{route('customerupload')}}" accept-charset="UTF-8" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="modal-body">   
                <div class="form-group">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="kode">File</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="formUpload" name="formUpload">
                                        <label class="custom-file-label" for="formUpload">Choose file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="cmdUpload">Upload</span>
                                    </div>
                                </div>
                            </div>
                        </div>  
                        <div class="col-12">
                            <a class="btn btn-info btn-xs" href="{{route('app.files', 'file_template_customer')}}" target="_blank"><i class="fa fa-download"></i>Template Document</a>
                        </div>
                    </div>
                </div>
            </div>   
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="card card-primary card-outline">
    <div class="card-header">
        <div class="row">
            <div class="col-4">
                <div class="form-group">                                        
                    <span class="label label-default">Customer / Deskripsi</span>
                    <input id="sSearch" class="form-control form-control-sm" name="sSearch" type="text">
                </div>
            </div>
            <div class="col-3">
                <div class="btn-group">
                    <button class="btn btn-sm btn-primary" id="sCmd"><i class="fa fa-search"></i>&nbsp;Cari</button>
                    <button class="btn btn-sm btn-success" alt="Tambah" data-toggle="modal" data-target="#modal-form"><i class="fa fa-plus-circle"></i>&nbsp;Tambah</button>
                    <button class="btn btn-sm btn-warning" alt="Upload" data-toggle="modal" data-target="#modal-form-upload"><i class="fa fa-file-upload"></i>&nbsp;Upload</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card-header -->
        <div class="card-body">  
            <table id="tables" class="table table-hover">
                <thead>
                    <tr>
                        <th></th>
                        @php
                        $lstTbl = [
                            'tcustomer' => 'Customer', 
                            'tnama1' => 'Nama', 
                            'tkota' => 'Kota', 
                            'tjalan1' => 'Jalan', 
                            'tcountry' => 'Country', 
                            'tgroup' => 'Group'
                        ];
                        
                        foreach($lstTbl as $k => $v)
                        {
                            echo '<th class="'.$k.'">'.$v.'</th>';
                        }
                        @endphp
                    </tr>
                </thead>
            </table>
        </div>
    <!-- /.card-body -->
</div>
@stop

@section('js')    
    <script>
        var tables = null;
        $(function(e)
        {
            bsCustomFileInput.init();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            tables = $('#tables').DataTable({
                "sPaginationType": "full_numbers",
                "searching":false,
                "ordering": true,
                "deferRender": true,
                "processing": true,
                "serverSide": true,
                "autoWidth": false,
                "lengthMenu": [100, 500, 1000, 1500, 2000 ],
                "ajax":
                {
                    "url"       : "{{ route('customertable') }}",
                    "type"      : 'POST',
                    data: function (d) 
                    {
                        d.search     = $('#sSearch').val();
                        d.skota     = $('#sKota').val();
                        d.country     = $('#sCountry').val();
                        d.group     = $('#sGroup').val();
                    }
                },
                "columnDefs"    :[
                {
                    "targets": 0,
                    "className":      'btndel',
                    "orderable":      false,
                    "data"     :           null,
                    "defaultContent": '<div class="btn-group"><button class="btn btn-sm btn-primary btnedit" data-toggle="modal" data-target="#modal-form"><i class="fa fa-edit"></i></button><button class="btn btn-sm btn-danger btndelete"><i class="fa fa-eraser"></i></button></div>'
                },
                {
                        targets : 'tcustomer',
                        data: "kode"
                },
                {
                        targets : 'tnama1',
                        data: "nama1"
                },
                {
                        targets : 'tkota',
                        data: "kota"
                },
                {
                        targets : 'tjalan1',
                        data: "jalan1"
                },
                {
                        targets : 'tcountry',
                        data: "country.kode"
                },
                {
                        targets : 'tgroup',
                        data: "group.kode"
                }]
            });

            $('#tables tbody').on('click', '.btnedit', function () 
            {
                var tr = $(this).closest('tr');
                var row = tables.row( tr );
                var datas = row.data();
                
                $('#id').val(datas.id);
                $('#kode').val(datas.kode);
                $('#nama1').val(datas.nama1);
                $('#nama2').val(datas.nama2);
                $('#kota').val(datas.kota);
                $('#jalan1').val(datas.jalan1);
                $('#jalan2').val(datas.jalan2);

                var newOption = new Option(datas.country.kode, datas.country.id, false, false);
                $('#country_id').append(newOption).trigger('change');

                var newOption = new Option(datas.group.kode, datas.group.id, false, false);
                $('#group_id').append(newOption).trigger('change');

                
            });
            
            $('#tables tbody').on('click', '.btndelete', function () 
            {
                var tr = $(this).closest('tr');
                var row = tables.row( tr );
                var datas = row.data();
                
                if(confirm('Apakah Anda yakin menghapus data ini?'))
                {
                    $.ajax(
                    {
                        url         : '{{route("customerdelete")}}',
                        dataType    : 'JSON',
                        type        : 'POST',
                        data        : {sId : datas.id} ,
                        beforeSend  : function(xhr)
                        {
                            toastOverlay.fire({
                                icon: 'warning',
                                title: 'Sedang memproses hapus data',
                                onBeforeOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success(result,status,xhr)
                        {
                            if(result.status == 1)
                            {
                                Toast.fire({
                                    icon: 'success',
                                    title: result.msg
                                });
                            }
                            else
                            {
                                if(Array.isArray(result.msg))
                                {
                                    var str = "";
                                    for(var i = 0 ; i < result.msg.length ; i++ )
                                    {
                                        str += result.msg[i]+"<br>";
                                    }
                                    Toast.fire({
                                        icon: 'error',
                                        title: str
                                    });
                                }

                            }
                            tables.ajax.reload();
                        },                        
                        error: function(jqXHR, textStatus, errorThrown) { 
                            /* implementation goes here */ 
                            toastOverlay.close();
                            console.log(jqXHR.responseText);
                        }
                    });

                    return false;
                }
            });

            $('#sCmd').on('click', function(e)
            {
                tables.ajax.reload()
            });
            
            $('#form_data').submit( function(e)
            {
                e.preventDefault();
                const data = $(this).serialize();
                
                $.ajax(
                {
                    url         : $(this).attr('action'),
                    dataType    : 'json',
                    type        : 'POST',
                    data        : data ,
                    success(result,status,xhr)
                    {
                        if(result.status == 1)
                        {
                            reset();
                            
                            Toast.fire({
                                icon: 'success',
                                title: result.msg
                            });
                        }
                        else
                        {
                            if(Array.isArray(result.msg))
                            {
                                var str = "";
                                for(var i = 0 ; i < result.msg.length ; i++ )
                                {
                                    str += result.msg[i]+"<br>";
                                }
                                Toast.fire({
                                    icon: 'error',
                                    title: str
                                });
                            }
                            else
                            {
                                Toast.fire({
                                    icon: 'error',
                                    title: result.msg
                                });
                            }
                            
                        }
                        tables.ajax.reload();
                    },
                    error: function(jqXHR, textStatus, errorThrown) { 
                        /* implementation goes here */                        
                        console.log(jqXHR.responseText);
                    }
                    
                });
                
                return false;
            });
            
            $('#cmdUpload').on('click', function(e)
            {
                let frm = document.getElementById('form_data_upload');
                let datas = new FormData(frm);

                $.ajax(
                {
                    url         : $('#form_data_upload').attr('action'),
                    dataType    : 'JSON',
                    type        : 'POST',
                    data        : datas ,
                    processData: false,
                    contentType: false,
                    beforeSend  : function(xhr)
                    {
                        toastOverlay.fire({
                            icon: 'warning',
                            title: 'Sedang memproses data upload'
                        });
                    },
                    success(result,status,xhr)
                    {
                        toastOverlay.close();
                        if(result.status == 1)
                        {
                            Toast.fire({
                                icon: 'success',
                                title: result.msg
                            });
                        }
                        else
                        {
                            if(Array.isArray(result.msg))
                            {
                                var str = "";
                                for(var i = 0 ; i < result.msg.length ; i++ )
                                {
                                    str += result.msg[i]+"<br>";
                                }
                                Toast.fire({
                                    icon: 'error',
                                    title: str
                                });
                                $('#tipe_exim').attr('disabled','disabled');
                            }
                            
                        }
                        tables.ajax.reload();
                    },
                    error: function(jqXHR, textStatus, errorThrown) { 
                        toastOverlay.close();
                        /* implementation goes here */ 
                        console.log(jqXHR.responseText);
                    }
                });
            });
            
            $('#modal-form').on('hidden.bs.modal', function (e) 
            {
                reset();
            });
            
            $('#country_id').select2({
                placeholder: "",
                allowClear: true,
                minimumInputLength: 0,
                delay: 250,
                ajax: {
                    url: "{{route('masteroptionselectcountry')}}",
                    dataType    : 'json',
                    type : 'post',
                    data: function (params) 
                    {
                        var query = {
                            q: params.term
                        }
                        
                        return query;
                    },
                    processResults: function (data) 
                    {
                        return {
                            results: data.items
                        };
                    },
                    cache: true
                }
            });
            
            $('#group_id').select2({
                placeholder: "",
                allowClear: true,
                minimumInputLength: 0,
                delay: 250,
                ajax: {
                    url: "{{route('masteroptionselectgroup')}}",
                    dataType    : 'json',
                    type : 'post',
                    data: function (params) 
                    {
                        var query = {
                            q: params.term
                        }
                        
                        return query;
                    },
                    processResults: function (data) 
                    {
                        return {
                            results: data.items
                        };
                    },
                    cache: true
                }
            });
            
        });

        function reset()
        {
            document.getElementById("form_data").reset(); 
            $('#country_id').val("").trigger('change');
            $('#group_id').val("").trigger('change');
            tables.ajax.reload();
        }
    </script>
@stop