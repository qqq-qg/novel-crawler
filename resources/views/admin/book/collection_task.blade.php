@extends('layout.admin')

@section('content')
    <table class="table table-bordered table-hover bg-white text-center">
        <tr>
            <td width="50">编号</td>
            <td align="left">名称</td>
            <td align="left">分类</td>
            <td align="left">URL</td>
            <td align="left">规则名称</td>
            <td align="left">页数</td>
            <td align="left">状态</td>
            <td width="150">操作</td>
        </tr>
        @if(count($lists) > 0)
            @foreach($lists as $v)
                <tr>
                    <td>{{ $v['id'] }}</td>
                    <td align="left">{{ $v->title }}</td>
                    <td align="left">{{ $v->category->name ?? '' }}</td>
                    <td align="left">{{ $v->from_url }}</td>
                    <td align="left">{{ $v->rule->title }}</td>
                    <td align="left">{{ $v->page_limit }}</td>
                    <td align="left">{{ $v->task_code }}</td>
                    <td>
                        <button class="btn btn-sm btn-info" id="edit_{{ $v['id'] }}" data="{{ json_encode($v) }}"
                                onclick="Edit({{ $v['id'] }})">编辑
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="Delete({{ $v['id'] }})">删除</button>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7">
                    未找到数据
                </td>
            </tr>
        @endif
    </table>
    <button class="btn btn-success" data-toggle="modal" data-target="#createModal">添加任务</button>
    <script>

    var deleteModal = '#deleteModal';
    var createModal = '#createModal';

    function Delete(id, name) {
      name = name ? name : 'id';
      $(deleteModal).find('input[name=' + name + ']').val(id);
      $(deleteModal).modal('show');
    }

    function Edit(id) {
      var json = $('#edit_' + id).attr('data');
      json = JSON.parse(json);
      $.each(json, function(k, v) {
        $(createModal).find('input[name=' + k + ']').val(v);
        $(createModal).find('select[name=' + k + ']').val(v);
      });

      $(createModal).modal('show');
    }
    </script>

    {{--delete--}}
    @include('admin.modal.delete' , ['formurl' => route('Book.deleteCollectionTask')])

    {{--create--}}
    <div class="modal inmodal" id="createModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content animated flipIn&nbsp;X">
                <form action="{{route('Book.createCollectionTask')}}" method="POST" class="form-horizontal">
                    {!! csrf_field() !!}
                    <input type="hidden" name="id" id="id">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        <h4 class="modal-title">添加任务</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">任务名称</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="title" value="{{ old('title') }}"
                                       placeholder="任务名称[必填]">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">分类</label>
                            <div class="col-sm-10">
                                <select name="cate_id" id="cate_id" class="form-control">
                                    <option value="">--请选择规则--</option>
                                    @foreach($categories??[] as $v)
                                        <option value="{{$v['id']}}">{{$v['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">采集URL</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="from_url" value="{{ old('from_url') }}"
                                       placeholder="http://xxx.com/xxx/xxx/{$page}">
                                <span class="help-block m-b-none">{$page}表示页码</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">采集规则</label>
                            <div class="col-sm-10">
                                <select name="rule_id" id="rule_id" class="form-control">
                                    <option value="">--请选择规则--</option>
                                    @foreach($rules??[] as $v)
                                        <option value="{{$v['id']}}">{{$v['title']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">采集页数</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" name="page_limit"
                                       value="" placeholder="分类/排行采集页数">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">尝试次数</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" name="retries"
                                       value="" placeholder="采集超时时尝试重新采集次数">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                        <button type="submit" class="btn btn-primary">确定</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection('content')
