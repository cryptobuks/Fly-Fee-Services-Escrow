@extends('admin.layouts.app')
@section('title', trans($page_title))
@section('content')

    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            @if(adminAccessRoute(config('role.remit_operation.access.add')))
            <div class="d-flex justify-content-end mb-2 text-right">
                <button data-toggle="modal" data-target="#btn_add" type="button" class="btn btn-primary btn-sm"><i
                        class="fa fa-plus-circle"></i> {{trans('Add Service')}} </button>
            </div>
            @endif


            @forelse($countryServices as $k => $countryService)
                @php
                    $serviceItem = collect($serviceList)->firstWhere('id',$k);
                @endphp
                <div class="card border-primary">
                    <div class="card-header bg-primary p-3 d-flex justify-content-between">
                        <h3 class="card-title text-white mb-0">{{$serviceItem->name}}</h3>
                        <a href="{{route('admin.country.service.charge',[$country,$serviceItem])}}" class="btn btn-dark btn-sm border-2"><i
                                class="fa fa-file-invoice-dollar"></i> {{trans('Manage Charge')}} </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="categories-show-table table table-hover table-striped table-bordered">
                                <thead class="thead-dark">
                                <tr>
                                    <th scope="col">@lang('SL')</th>
                                    <th scope="col">@lang('Name')</th>
                                    <th scope="col">@lang('Status')</th>
                                    @if(adminAccessRoute(config('role.remit_operation.access.edit')))
                                    <th scope="col">@lang('Action')</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($countryService as $key => $data)
                                    <tr>
                                        <td data-label="@lang('SL')">{{++$key}}</td>
                                        <td data-label="@lang('Name')">
                                            <h5 class="text-dark mb-0 font-16 ">@lang($data->name)</h5>
                                        </td>
                                        <td data-label="@lang('Status')">
                                <span
                                    class="badge badge-pill {{ $data->status == 0 ? 'badge-danger' : 'badge-success' }}">{{ $data->status == 0 ? 'Inactive' : 'Active' }}</span>
                                        </td>

                                        @if(adminAccessRoute(config('role.remit_operation.access.edit')))
                                        <td data-label="@lang('Action')">
                                            <a href="javascript:void(0)"
                                               data-id="{{$data->id}}"
                                               data-name="{{$data->name}}"
                                               data-service_id="{{$data->service_id}}"
                                               data-status="{{$data->status}}"
                                               data-services_form="{{($data->services_form == null) ? null :  json_encode(@$data->services_form)}}"
                                               data-toggle="modal" data-target="#editModal" data-original-title="Edit"
                                               class="btn btn-primary btn-sm editButton"><i class="fa fa-edit"></i></a>
                                        </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center text-danger" colspan="9">@lang('No Data Found')</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <h4>@lang('No Data Found')</h4>
            @endforelse

        </div>
    </div>


    <div class="modal  fade " id="btn_add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <form action="{{route('admin.country.service.store',$country)}}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus-circle"></i> {{trans('Add New')}}
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    </div>


                    <div class="modal-body">
                        <div class="row ">
                            <div class="col-md-6 form-group">
                                <label for="inputName" class="control-label"><strong>{{trans('Service Name')}}
                                        :</strong></label>
                                <input type="text" class="form-control " name="name"
                                       placeholder="{{trans('Service Name')}}" value="{{old('name')}}">

                                @error('name')
                                <span class="text-danger">{{ trans($message) }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group ">
                                <label for="inputName" class="control-label d-block"><strong>{{trans('Category')}}
                                        :</strong></label>
                                <select class="form-control  w-100"
                                        data-live-search="true" name="service_id"
                                        required="">
                                    <option disabled selected>@lang("Select Service")</option>
                                    @foreach($country->facilities as $item)
                                        <option value="{{$item->id}}">{{trans($item->name)}}</option>
                                    @endforeach
                                </select>
                                <br>
                                @error('service_id')
                                <span class="text-danger">{{ trans($message) }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group ">
                                <label for="inputName" class="control-label d-block"><strong>{{trans('Status')}}
                                        :</strong></label>

                                <select class="form-control  w-100"
                                        data-live-search="true" name="status"
                                        required="">
                                    <option disabled selected>@lang("Select Status")</option>
                                    <option value="1">{{trans('Active')}}</option>
                                    <option value="0">{{trans('Deactive')}}</option>
                                </select>
                                <br>

                                <br>
                                @error('status')
                                <span class="text-danger">{{ trans($message) }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <br><br>
                                <a href="javascript:void(0)" class="btn btn-success btn-sm float-right generate"><i
                                        class="fa fa-plus-circle"></i> {{trans('Add Field')}}</a>

                            </div>
                        </div>

                        <div class="row addedField mt-3">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">
                            {{trans('Close')}}
                        </button>
                        <button type="submit" class="btn btn-primary"> {{trans('Save')}}</button>
                    </div>

                </form>

            </div>
        </div>
    </div>


    <div class="modal  fade " id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <form action="{{route('admin.country.service.update',$country)}}" method="post">
                    @method('patch')
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel"><i
                                class="fa fa-sync-alt"></i> {{trans('Update Service')}}
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    </div>


                    <div class="modal-body">
                        <input type="hidden" name="id" class="edit_id">
                        <div class="row ">
                            <div class="col-md-6 form-group">
                                <label for="inputName" class="control-label"><strong>{{trans('Service Name')}}
                                        :</strong></label>
                                <input type="text" class="form-control edit_name" name="name"
                                       placeholder="{{trans('Service Name')}}" value="{{old('name')}}">
                                @error('name')
                                <span class="text-danger">{{ trans($message) }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group ">
                                <label for="inputName" class="control-label d-block"><strong>{{trans('Category')}}
                                        :</strong></label>
                                <select class="form-control  w-100 edit_service_id"
                                        data-live-search="true" name="service_id"
                                        required="">
                                    <option disabled>@lang("Select Service")</option>
                                    @foreach($country->facilities as $item)
                                        <option value="{{$item->id}}">{{trans($item->name)}}</option>
                                    @endforeach
                                </select>
                                <br>
                                @error('service_id')
                                <span class="text-danger">{{ trans($message) }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group ">
                                <label for="inputName" class="control-label d-block"><strong>{{trans('Status')}}
                                        :</strong></label>
                                <select class="form-control  w-100 edit_status"
                                        data-live-search="true" name="status"
                                        required="">
                                    <option disabled>@lang("Select Status")</option>
                                    <option value="1">{{trans('Active')}}</option>
                                    <option value="0">{{trans('Deactive')}}</option>
                                </select>
                                <br>
                                @error('status')
                                <span class="text-danger">{{ trans($message) }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <br><br>
                                <a href="javascript:void(0)" class="btn btn-success btn-sm float-right generate"><i
                                        class="fa fa-plus-circle"></i> {{trans('Add Field')}}</a>

                            </div>
                        </div>

                        <div class="row addedField mt-3">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">
                            {{trans('Close')}}
                        </button>
                        <button type="submit" class="btn btn-primary"> {{trans('Update')}}</button>
                    </div>

                </form>

            </div>
        </div>
    </div>




@endsection


@push('js')
    <script>
        "use strict";
        $(document).ready(function () {

            $(".generate").on('click', function () {
                var form = `<div class="col-md-12">
                                 <div class="card border-primary">

                                        <div class="card-header  bg-primary p-2 d-flex justify-content-between">
                                            <h5 class="card-title text-white font-weight-bold">{{trans('Field information')}}</h3>
                                            <button  class="btn  btn-danger btn-sm delete_desc" type="button">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>

                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4 form-group">
                                                    <label>{{trans('Field Name')}}</label>
                                                    <input name="field_name[]" class="form-control " type="text" value="" required
                                                           placeholder="{{trans('Field Name')}}">
                                                </div>

                                                <div class="col-md-4 form-group">
                                                    <label>{{trans('Form Type')}}</label>
                                                    <select name="type[]" class="form-control  ">
                                                        <option value="text">{{trans('Input Text')}}</option>
                                                        <option value="textarea">{{trans('Textarea')}}</option>
                                                        <option value="file">{{trans('File upload')}}</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-4 form-group">
                                                    <label>{{trans('Field Length')}}</label>
                                                    <input name="field_length[]" class="form-control " type="number" min="2" value="" required
                                                           placeholder="{{trans('Length')}}">
                                                </div>

                                                <div class="col-md-4 form-group">
                                                    <label>{{trans('Field Length Type')}}</label>
                                                    <select name="length_type[]" class="form-control">
                                                        <option value="max">{{trans('Maximum Length')}}</option>
                                                        <option value="digits">{{trans('Fixed Length')}}</option>
                                                    </select>
                                                </div>



                                                <div class="col-md-4 form-group">
                                                    <label>{{trans('Form Validation')}}</label>
                                                    <select name="validation[]" class="form-control  ">
                                                        <option value="required">{{trans('Required')}}</option>
                                                        <option value="nullable">{{trans('Optional')}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                            </div> `;

                $('.addedField').append(form)
            });

            $('select').select2({
                width: '100%'
            });


            $(document).on('click', '.editButton', function (e) {
                $('.addedField').html('')

                var obj = $(this).data()
                $('.edit_id').val(obj.id)
                $('.edit_name').val(obj.name)
                $(".edit_status").val(obj.status).trigger('change');
                $(".edit_service_id").val(obj.service_id).trigger('change');

                if (obj.services_form == 'null') {

                } else {
                    var objData = Object.entries(obj.services_form);

                    var form = '';
                    for (let i = 0; i < objData.length; i++) {
                        form += `<div class="col-md-12">

                                    <div class="card border-primary">

                                        <div class="card-header  bg-primary p-2 d-flex justify-content-between">
                                            <h5 class="card-title text-white font-weight-bold">{{trans('Field information')}}</h3>
                                            <button  class="btn  btn-danger btn-sm delete_desc" type="button">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>

                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4 form-group">
                                                    <label>{{trans('Field Name')}}</label>
                                                    <input name="field_name[]" class="form-control"
                                                     value="${objData[i][1].field_level}"
                                                     type="text" required
                                                           placeholder="{{trans('Field Name')}}">
                                                </div>

                                                <div class="col-md-4 form-group">
                                                    <label>{{trans('Form Type')}}</label>
                                                    <select name="type[]" class="form-control  ">
                                                        <option value="text" ${(objData[i][1].type === 'text' ? 'selected="selected"' : '')}>{{trans('Input Text')}}</option>
                                                        <option value="textarea" ${(objData[i][1].type === 'textarea' ? 'selected="selected"' : '')}>{{trans('Textarea')}}</option>
                                                        <option value="file" ${(objData[i][1].type === 'file' ? 'selected="selected"' : '')}>{{trans('File upload')}}</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-4 form-group">
                                                    <label>{{trans('Field Length')}}</label>
                                                    <input name="field_length[]" class="form-control " type="number" min="2" required
                                                    value="${objData[i][1].field_length}"
                                                           placeholder="{{trans('Length')}}">
                                                </div>

                                                <div class="col-md-4 form-group">
                                                    <label>{{trans('Field Length Type')}}</label>
                                                    <select name="length_type[]" class="form-control">
                                                        <option value="max"  ${(objData[i][1].length_type === 'max' ? 'selected="selected"' : '')}>{{trans('Maximum Length')}}</option>
                                                        <option value="digits"  ${(objData[i][1].length_type === 'digits' ? 'selected="selected"' : '')}>{{trans('Fixed Length')}}</option>
                                                    </select>
                                                </div>



                                                <div class="col-md-4 form-group">
                                                    <label>{{trans('Form Validation')}}</label>
                                                    <select name="validation[]" class="form-control  ">
                                                            <option value="required" ${(objData[i][1].validation === 'required' ? 'selected="selected"' : '')}>{{trans('Required')}}</option>
                                                            <option value="nullable" ${(objData[i][1].validation === 'nullable' ? 'selected="selected"' : '')}>{{trans('Optional')}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                            </div> `;
                    }
                    $('.addedField').append(form)

                }

            });

            $(document).on('click', '.delete_desc', function () {
                $(this).closest('.card').parent().remove();
            });


        });

    </script>
@endpush
