@extends('adminlte::page')

@section('title', 'Material')

@section('content_header')
    <h1>Material</h1>
@stop

@section('content')
<div class="modal fade" id="modal-form">
    <div class="modal-dialog">
        <div class="modal-content bg-secondary">
            <div class="modal-header">
                <h4 class="modal-title">Form Material</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_data" action="{{route('materialsave')}}" accept-charset="UTF-8" >
                {{csrf_field()}}
                <input type="hidden" name="id" id="id">
                <div class="modal-body">            
                    <div class="row">
                        <div class="col-12">                                
                            <div class="form-group">
                                <label for="kode">Material</label>
                                <input type="text" class="form-control form-control-sm" id="kode" name="kode">
                            </div>
                            <div class="form-group">
                                <label for="deskripsi">Deskripsi</label>
                                <input type="text" class="form-control form-control-sm" id="deskripsi" name="deskripsi">
                            </div>
                            <div class="form-group">
                                <label for="mrp_id">MRP Group</label>
                                <select name="mrp_id" id="mrp_id" class="form-control form-control-sm select2" style="width:100%"></select>
                            </div>
                            <div class="form-group">
                                <label for="mtype_id">Material Type</label>
                                <select name="mtype_id" id="mtype_id" class="form-control form-control-sm select2" style="width:100%"></select>
                            </div>
                            <div class="form-group">
                                <label for="mgroup_id">Matl Group</label>
                                <select name="mgroup_id" id="mgroup_id" class="form-control form-control-sm select2" style="width:100%"></select>
                            </div>
                            <div class="form-group">
                                <label for="bunit_id">Base Unit</label>
                                <select name="bunit_id" id="bunit_id" class="form-control form-control-sm select2" style="width:100%"></select>
                            </div>
                            <div class="form-group">
                                <label for="valcl_id">ValCl</label>
                                <select name="valcl_id" id="valcl_id" class="form-control form-control-sm select2" style="width:100%"></select>
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
                <h4 class="modal-title"><i class="fa fa-upload"></i>Form Upload Material</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_data_upload" action="{{route('materialupload')}}" accept-charset="UTF-8" enctype="multipart/form-data">
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
                            <a class="btn btn-info btn-xs" href="{{route('app.files', 'file_template_material')}}" target="_blank"><i class="fa fa-download"></i>Template Document</a>
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
                    <span class="label label-default">Material / Deskripsi</span>
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
                            'tmaterial' => 'Material', 
                            'tdeskripsi' => 'Deskripsi', 
                            'tmrp' => 'MRP Group', 
                            'tmrpdes' => 'MRP Deskripsi', 
                            'tmtyp' => 'MTyp', 
                            'tmatl' => 'Matl Group', 
                            'tbun' => 'BUn', 
                            'tvalcl' => 'ValCl'
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
                    "url"       : "{{ route('materialtable') }}",
                    "type"      : 'POST',
                    data: function (d) 
                    {
                        d.search     = $('#sSearch').val();
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
                        targets : 'tmaterial',
                        data: "kode"
                },
                {
                        targets : 'tdeskripsi',
                        data: "deskripsi"
                },
                {
                        targets : 'tmrp',
                        data: "mrp.kode"
                },
                {
                        targets : 'tmrpdes',
                        data: "mrp.deskripsi"
                },
                {
                        targets : 'tmtyp',
                        data: "mtype.kode"
                },
                {
                        targets : 'tmatl',
                        data: "mgroup.kode"
                },
                {
                        targets : 'tbun',
                        data: "bunit.kode"
                },
                {
                        targets : 'tvalcl',
                        data: "valcl.kode"
                }]
            });

            $('#tables tbody').on('click', '.btnedit', function () 
            {
                var tr = $(this).closest('tr');
                var row = tables.row( tr );
                var datas = row.data();
                
                $('#id').val(datas.id);
                $('#kode').val(datas.kode);
                $('#deskripsi').val(datas.deskripsi);

                var newOption = new Option(datas.mrp.kode, datas.mrp.id, false, false);
                $('#mrp_id').append(newOption).trigger('change');

                var newOption = new Option(datas.mtype.kode, datas.mtype.id, false, false);
                $('#mtype_id').append(newOption).trigger('change');

                var newOption = new Option(datas.mgroup.kode, datas.mgroup.id, false, false);
                $('#mgroup_id').append(newOption).trigger('change');

                var newOption = new Option(datas.bunit.kode, datas.bunit.id, false, false);
                $('#bunit_id').append(newOption).trigger('change');

                var newOption = new Option(datas.valcl.kode, datas.valcl.id, false, false);
                $('#valcl_id').append(newOption).trigger('change');

                
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
                        url         : '{{route("materialdelete")}}',
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
            
            $('#mrp_id').select2({
                placeholder: "",
                allowClear: true,
                minimumInputLength: 0,
                delay: 250,
                ajax: {
                    url: "{{route('masteroptionselectmrp')}}",
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
            
            $('#mtype_id').select2({
                placeholder: "",
                allowClear: true,
                minimumInputLength: 0,
                delay: 250,
                ajax: {
                    url: "{{route('masteroptionselectmtype')}}",
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
            
            $('#mgroup_id').select2({
                placeholder: "",
                allowClear: true,
                minimumInputLength: 0,
                delay: 250,
                ajax: {
                    url: "{{route('masteroptionselectmgroup')}}",
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
            
            $('#bunit_id').select2({
                placeholder: "",
                allowClear: true,
                minimumInputLength: 0,
                delay: 250,
                ajax: {
                    url: "{{route('masteroptionselectbunit')}}",
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
            
            $('#valcl_id').select2({
                placeholder: "",
                allowClear: true,
                minimumInputLength: 0,
                delay: 250,
                ajax: {
                    url: "{{route('masteroptionselectvalcl')}}",
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
            $('#mrp_id').val("").trigger('change');
            $('#mtype_id').val("").trigger('change');
            $('#mgroup_id').val("").trigger('change');
            $('#bunit_id').val("").trigger('change');
            $('#valcl_id').val("").trigger('change');
            tables.ajax.reload();
        }
    </script>
@stop