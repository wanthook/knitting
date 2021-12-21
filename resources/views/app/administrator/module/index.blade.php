@extends('adminlte::page')

@section('title', 'Module')

@section('content_header')
    <h1>Module</h1>
@stop

@section('content')
<div class="modal fade" id="modal-form">
    <div class="modal-dialog">
        <div class="modal-content bg-secondary">
            <div class="modal-header">
                <h4 class="modal-title">Form Module</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_data" action="{{route('modulesave')}}" accept-charset="UTF-8" >
                {{csrf_field()}}
                <input type="hidden" name="id" id="id">
                <div class="modal-body">            
                    <div class="form-group">
                        <label for="nama">Nama Modul</label>
                        <input type="text" class="form-control" id="nama" name="nama">
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <input type="text" class="form-control" id="deskripsi" name="deskripsi">
                    </div>
                    <div class="form-group">
                        <label for="route">Route</label>
                        <select class="form-control select2" name="route" id="route">
                            <option value="">--Silakan Pilih--</option>
                            @foreach(Route::getRoutes() as $route)
                            {
                            <option value="{{$route->getName()}}">{{$route->getName().' - '.$route->uri()}}</option>
                            }
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="parent">Parent</label>
                        <select name="parent_id" id="parent_id" class="form-control select2" style="width:100%"></select>
                    </div>
                    <div class="form-group">
                        <label for="param">Param</label>
                        <input type="text" class="form-control" id="param" name="param">
                    </div>
                    <div class="form-group">
                        <label for="icon">Icon</label>
                        <input type="text" class="form-control" id="icon" name="icon">
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
<div class="card card-primary card-outline">
    <div class="card-header">
        <div class="row">
            <div class="col-4">
                <div class="form-group">                                        
                    <span class="label label-default">Kode/Deskripsi</span>
                    <input id="sSearch" class="form-control form-control-sm" name="sSearch" type="text">
                </div>
            </div>
            <div class="col-3">
                <div class="btn-group">
                    <button class="btn btn-sm btn-primary" id="sCmd"><i class="fa fa-search"></i>&nbsp;Cari</button>
                    <button class="btn btn-sm btn-success" alt="Tambah" data-toggle="modal" data-target="#modal-form"><i class="fa fa-plus-circle"></i>&nbsp;Tambah</button>
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
                        $lstTbl = ['tmenu' => 'Menu', 
                        'tdeskripsi' => 'Deskripsi', 
                        'tparent' => 'Parent',
                        'troute' => 'Route',
                        'ticon' => 'Icon'];
                        
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
                    "url"       : "{{ route('moduletable') }}",
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
                    "defaultContent": '<button class="btn btn-sm btn-primary btnedit" data-toggle="modal" data-target="#modal-form"><i class="fa fa-edit"></i></button><button class="btn btn-sm btn-danger btndelete"><i class="fa fa-eraser"></i></button>'
                },
                {
                        targets : 'tmenu',
                        data: "nama"
                },
                {
                        targets : 'tdeskripsi',
                        data: "deskripsi"
                },
                {
                        targets : 'tparent',
                        data: function(datas)
                        {
                            if(datas.ancestors.length > 0)
                            {
                                return datas.ancestors[0].nama;
                            }
                            else
                            {
                                return 'Head';
                            }
                        }
                },
                {
                        targets : 'troute',
                        data: "route"
                },
                {
                        targets : 'ticon',
                        data: function(datas)
                        {
                            if(datas.icon)
                            {
                                return '<i class="'+datas.icon+'"></i>';
                            }
                            else
                            {
                                return '';
                            }
                        }
                }]
            });

            $('#tables tbody').on('click', '.btnedit', function () 
            {
                var tr = $(this).closest('tr');
                var row = tables.row( tr );
                var datas = row.data();
                
                $('#id').val(datas.id);
                $('#nama').val(datas.nama);
                $('#deskripsi').val(datas.deskripsi);
                $('#route').val(datas.route);

                // $('#parent').val(datas.parent);
                if(datas.ancestors.length > 0)
                {
                    var newOption = new Option(datas.ancestors[0].nama+' - '+datas.ancestors[0].deskripsi, datas.ancestors[0].id, false, false);
                    $('#parent_id').append(newOption).trigger('change');
                }

                $('#param').val(datas.param);
                $('#icon').val(datas.icon);
                
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
                        url         : '{{route("moduledelete")}}',
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
            
            $('#modal-form').on('hidden.bs.modal', function (e) 
            {
                reset();
            });
            
            $('#parent_id').select2({
                placeholder: "",
                allowClear: true,
                minimumInputLength: 0,
                delay: 250,
                ajax: {
                    url: "{{route('moduleselectparent')}}",
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
            $('#parent_id').val("").trigger('change');
            tables.ajax.reload();
        }
    </script>
@stop