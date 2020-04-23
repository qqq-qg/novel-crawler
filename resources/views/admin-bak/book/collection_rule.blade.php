@extends('layout.admin')

@section('content')
    <table class="table table-bordered table-hover bg-white text-center">
        <tr>
            <td width="50">编号</td>
            <td align="left">标题</td>
            <td align="left">规则</td>
            <td width="150">操作</td>
        </tr>
        @if(count($lists) > 0)
            @foreach($lists as $v)
                <tr>
                    <td>{{ $v['id'] }}</td>
                    <td align="left">{{ $v['title'] }}</td>
                    <td align="left">{{ json_encode($v['rule_json']) }}</td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick="Edit({{ $v['id'] }})">编辑</button>
                        <button class="btn btn-sm btn-danger" onclick="Delete({{ $v['id'] }})">删除</button>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4">
                    {!! $lists->render() !!}
                </td>
            </tr>
        @else
            <tr>
                <td colspan="7">
                    未找到数据
                </td>
            </tr>
        @endif
    </table>
    <button class="btn btn-success" onclick="Add()">添加规则</button>
    <script>

        var deleteModal = '#deleteModal';

        function Delete(id, name) {
            name = name ? name : 'id';
            $(deleteModal).find('input[name=' + name + ']').val(id);
            $(deleteModal).modal('show');
        }

        function Add() {
            window.location.href = '{{route('Book.getCreateCollectionRule')}}';
        }

        function Edit(id) {
            window.location.href = '{{route('Book.getCreateCollectionRule')}}' + '?id=' + id;
        }
    </script>

    {{--delete--}}
    @include('admin.modal.delete' , ['formurl' => route('Book.deleteCollectionRule')])
@endsection('content')
