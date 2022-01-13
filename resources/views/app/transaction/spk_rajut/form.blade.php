@extends('adminlte::page')

@section('title', 'Form SPK Rajut')

@section('content_header')
    <h1>Form SPK Rajut</h1>
@stop

@section('content')
<div class="modal fade" id="modal-form">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-secondary">
            <div class="modal-header">
                <h4 class="modal-title">Form Detail SPK Rajut</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_data_detail" action="{{route('spkrajutsavedetail')}}" accept-charset="UTF-8" >
                {{csrf_field()}}
                <input type="hidden" name="detail_id" id="detail_id">
                <input type="hidden" name="spk_rajut_id" id="spk_rajut_id">
                <div class="modal-body">            
                    <div class="row">
                        <div class="col-6">                                
                            <div class="form-group">
                                <label for="det_mesin_id">Mesin</label>
                                <select name="det_mesin_id" id="det_mesin_id" class="form-control form-control-sm select2" style="width:100%"></select>
                            </div>       
                            <div class="form-group">
                                <label for="det_material_id">Material</label>
                                <select name="det_material_id" id="det_material_id" class="form-control form-control-sm select2" style="width:100%"></select>
                            </div>   
                            <div class="form-group">
                                <label for="det_raw_material_id">Raw Material</label>
                                <select name="det_raw_material_id" id="det_raw_material_id" class="form-control form-control-sm select2" style="width:100%"></select>
                            </div>
                            <div class="form-group">
                                <label for="det_warna">Warna</label>
                                <input type="text" class="form-control form-control-sm" id="det_warna" name="det_warna">
                            </div>
                        </div>
                        <div class="col-6">   
                            <div class="form-group">
                                <label for="det_greige">Greige</label>
                                <input type="text" class="form-control form-control-sm" id="det_greige" name="det_greige">
                            </div>   
                            <div class="form-group">
                                <label for="det_finish">Finish</label>
                                <input type="text" class="form-control form-control-sm" id="det_finish" name="det_finish">
                            </div>              
                            <div class="form-group">
                                <label for="det_size_finish">Size Finish</label>
                                <input type="text" class="form-control form-control-sm" id="det_size_finish" name="det_size_finish">
                            </div>
                            <div class="form-group">
                                <label for="det_qty">Qty</label>
                                <input type="text" class="form-control form-control-sm" id="det_qty" name="det_qty">
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
<div class="card card-primary card-outline">
    <div class="card-header">
        <button id="cmdSimpan" class="btn btn-sm btn-primary float-right"><i class="fa fa-save"></i> Simpan SPK</button>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                {{ Form::model($var, ['route' => ['spkrajutsave'], 'id' => 'form_data', 'files' => true]) }}
                {{ Form::hidden('id',null, ['id' => 'id']) }}
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            {{ Form::label('no', 'No. SPK') }}
                            {{ Form::text('no', null, ['id' => 'key', 'class' => 'form-control form-control-sm', 'placeholder' => 'No. SPK']) }}
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            {{ Form::label('no', 'Customer') }}
                            @if($var->customers)
                            {{ Form::select('customer_id', [$var->customer_id => $var->customers->kode.' - '.$var->customers->nama1], $var->customer_id, ['id' => 'customer_id', 'class' => 'form-control form-control-sm select2', 'style'=> 'width: 100%;']) }}
                            @else
                            {{ Form::select('customer_id', [], null, ['id' => 'customer_id', 'class' => 'form-control form-control-sm select2', 'style'=> 'width: 100%;']) }}
                            @endif
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            {{ Form::label('tanggal', 'Tanggal') }}
                            {{ Form::date('tanggal', null, ['id' => 'tanggal', 'class' => 'form-control form-control-sm', 'placeholder' => 'Tanggal']) }}
                        </div>
                    </div>
                </div>
            </div>
            {{Form::close()}}
            <div class="col-12">
                <div class="btn-group float-right">
                    <button id="cmdTambah" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-form"><i class="fa fa-plus"></i> Tambah Mesin</button>
                </div>
            </div>
            <div class="col-12">
                <table id="tables" class="table table-hover">
                    <thead>
                        <tr>
                            <th></th>
                            @php
                            $lstTbl = [
                                'tmesin' => 'Mesin', 
                                'tmaterial' => 'Material', 
                                'tmaterialraw' => 'Raw Material', 
                                'twarna' => 'Warna', 
                                'tgreige' => 'Greige', 
                                'tfinish' => 'Finish', 
                                'tsizefinish' => 'Size Finish', 
                                'tqty' => 'Qty', 
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
        </div>
    </div>
</div>
@endsection

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
                "scrollX": true,
                "lengthMenu": [100, 500, 1000, 1500, 2000 ],
                "ajax":
                {
                    "url"       : "{{ route('spkrajuttabledetail') }}",
                    "type"      : 'POST',
                    data: function (d) 
                    {
                        d.id     = $('#id').val();
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
                        targets : 'tmesin',
                        data: "mesin.deskripsi"
                },
                {
                        targets : 'tmaterial',
                        data: function(rows)
                        {
                            return '<b>' + rows.material.kode+ '</b><br>' + rows.material.deskripsi;
                        }
                },
                {
                        targets : 'tmaterialraw',
                        data: function(rows)
                        {
                            return '<b>' + rows.rawmaterial.kode+ '</b><br>' + rows.rawmaterial.deskripsi;
                        }
                },
                {
                        targets : 'twarna',
                        data: "warna"
                },
                {
                        targets : 'tgreige',
                        data: "greige"
                },
                {
                        targets : 'tfinish',
                        data: "finish"
                },
                {
                        targets : 'tsizefinish',
                        data: "size_finish"
                },
                {
                        targets : 'tqty',
                        data: "qty"
                }]
            });

            $('#tables tbody').on('click', '.btnedit', function () 
            {
                var tr = $(this).closest('tr');
                var row = tables.row( tr );
                var datas = row.data();
                
                $('#detail_id').val(datas.id);
                $('#spk_rajut_id').val(datas.spk_rajut_id);

                $('#det_warna').val(datas.warna);
                $('#det_greige').val(datas.greige);
                $('#det_finish').val(datas.finish);
                $('#det_size_finish').val(datas.size_finish);
                $('#det_qty').val(datas.qty);

                var newOption = new Option(datas.mesin.deskripsi + " - " + datas.mesin.text, datas.mesin.id, false, false);
                $('#det_mesin_id').append(newOption).trigger('change');

                var newOption = new Option(datas.material.kode + " - " + datas.material.deskripsi, datas.material.id, false, false);
                $('#det_material_id').append(newOption).trigger('change');

                var newOption = new Option(datas.rawmaterial.kode + " - " + datas.rawmaterial.deskripsi, datas.rawmaterial.id, false, false);
                $('#det_raw_material_id').append(newOption).trigger('change');

                
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
                        url         : '{{route("spkrajutdeletedetail")}}',
                        dataType    : 'JSON',
                        type        : 'POST',
                        data        : {id : datas.id} ,
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

            $('#cmdSimpan').on('click', function(e)
            {
                e.preventDefault();

                $('#form_data').submit();
            });

            $('#cmdModalSave').on('click', function(e)
            {
                e.preventDefault();

                $('#form_data_detail').submit();
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
                            Toast.fire({
                                icon: 'success',
                                title: result.msg
                            });

                            setTimeout(() => {  window.location = "{{route('spkrajutlist')}}"; }, 2000);
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
            
            $('#form_data_detail').submit( function(e)
            {
                e.preventDefault();
                $('#spk_rajut_id').val($('#id').val());
                let data = $(this).serialize();
                
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

            $('#customer_id').select2({
                placeholder: "",
                allowClear: true,
                minimumInputLength: 0,
                delay: 250,
                ajax: {
                    url: "{{route('customerselect')}}",
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
                            results: $.map(data.items, function(obj, index) {
                                return { id: obj.id, text: obj.text + " - " + obj.nama};
                            })
                        };
                    },
                    cache: true
                }
            });

            $('#det_mesin_id').select2({
                placeholder: "",
                allowClear: true,
                minimumInputLength: 0,
                delay: 250,
                ajax: {
                    url: "{{route('mesinselect')}}",
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
                            results: $.map(data.items, function(obj, index) {
                                return { id: obj.id, text: obj.deskripsi + " - " + obj.text};
                            })
                        };
                    },
                    cache: true
                }
            });

            $('#det_material_id, #det_raw_material_id').select2({
                placeholder: "",
                allowClear: true,
                minimumInputLength: 0,
                delay: 250,
                ajax: {
                    url: "{{route('materialselect')}}",
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
                            results: $.map(data.items, function(obj, index) {
                                return { id: obj.id, text: obj.text + " - " + obj.deskripsi};
                            })
                        };
                    },
                    cache: true
                }
            });

            $('#modal-form').on('hidden.bs.modal', function (e) 
            {
                reset();
            });
        });

        function reset()
        {
            $('#detail_id').val('');
            document.getElementById("form_data_detail").reset(); 
            $('#det_mesin_id, #det_material_id, #det_raw_material_id').val("").trigger('change');
            tables.ajax.reload();
        }
    </script>
@stop