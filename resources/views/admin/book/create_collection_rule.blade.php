@extends('layout.admin')

@section('content')
  <style>
    .rule-table {
      width: 100%;
    }

    .rule-table input {
      display: inline-block;
      width: 120px;
      margin-top: 5px;
    }

    .rule-table input.rule-select {
      width: 200px;
    }

    .rule-table input.rule-page {
      width: 80px;
    }

    .rule-table input.rule-replace {
      width: 300px;
    }

  </style>
  <div class="ibox float-e-margins">
    <form method="post" class="form-horizontal" id="sform"
          action="{{ route('Book.createCollectionRule') }}">
      {!! csrf_field() !!}
      <div class="col-sm-12 col-md-12">
        <div class="ibox-title">
          @if(isset($id))
            <h5>编辑规则</h5>
            <input type="hidden" name="id" value="{{ $id }}">
          @else
            <h5>添加规则</h5>
          @endif
        </div>
        <div class="ibox-content">
          <div class="form-group">
            <label class="col-sm-2 control-label">规则名</label>
            <div class="col-sm-10">
              <input id="title" type="text" class="form-control" name="title" value="{{ $title??'' }}">
            </div>
          </div>
          <div class="hr-line-dashed"></div>

          <div class="form-group">
            <label class="col-sm-2 control-label">域名</label>
            <div class="col-sm-10">
              <input id="host" type="text" class="form-control" name="host"
                     value="{{ $rule_json['host']??'www.' }}"/>
              <span class="help-block m-b-none">不包含(http://，https://)</span>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">编码</label>
            <div class="col-sm-10">
              <input id="charset" type="text" class="form-control" name="charset"
                     value="{{ $rule_json['charset']??'utf-8' }}"/>
              <span class="help-block m-b-none">[utf-8|gbk|...]，默认utf-8</span>
            </div>
          </div>
          <div class="hr-line-dashed"></div>

          <div class="form-group">
            <label class="col-sm-2 control-label">排行列表</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="ranking[range]" placeholder="范围选择器 range"
                     value="{{ $rule_json['bookList']['ranking']['range']??'' }}"/>
              <table class="rule-table">
                <thead>
                <tr>
                  <th width="100">Key</th>
                  <th>Rule</th>
                  <th width="100">页数</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td>url</td>
                  <td>
                    <input class="form-control rule-select" name="ranking[url][]"
                           placeholder="CSS元素查询器[必填]"
                           value="{{$rule_json['bookList']['ranking']['rules']['url'][0]??''}}">
                    <input class="form-control" name="ranking[url][]" placeholder="属性[必填]"
                           value="{{$rule_json['bookList']['ranking']['rules']['url'][1]??''}}">
                    <input class="form-control" name="ranking[url][]" placeholder="过滤元素[选填]"
                           value="{{$rule_json['bookList']['ranking']['rules']['url'][2]??''}}">
                    <input class="form-control" name="ranking[url][]" placeholder="保留元素[选填]"
                           value="{{$rule_json['bookList']['ranking']['rules']['url'][3]??''}}">
                  </td>
                  <td>
                    <input type="number" class="form-control rule-page" name="ranking[page]"
                           value="{{$rule_json['bookList']['ranking']['page']??'1'}}">
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="hr-line-dashed"></div>

          <div class="form-group">
            <label class="col-sm-2 control-label">分类列表</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="category[range]" placeholder="范围选择器 range"
                     value="{{ $rule_json['bookList']['category']['range']??'' }}"/>
              <table class="rule-table">
                <thead>
                <tr>
                  <th width="100">Key</th>
                  <th>Rule</th>
                  <th width="100">页数</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td>url</td>
                  <td>
                    <input class="form-control rule-select" name="category[url][]"
                           placeholder="CSS元素查询器[必填]"
                           value="{{$rule_json['bookList']['category']['rules']['url'][0]??''}}">
                    <input class="form-control" name="category[url][]" placeholder="属性[必填]"
                           value="{{$rule_json['bookList']['category']['rules']['url'][1]??''}}">
                    <input class="form-control" name="category[url][]" placeholder="过滤元素[选填]"
                           value="{{$rule_json['bookList']['category']['rules']['url'][2]??''}}">
                    <input class="form-control" name="category[url][]" placeholder="保留元素[选填]"
                           value="{{$rule_json['bookList']['category']['rules']['url'][3]??''}}">
                  </td>
                  <td>
                    <input type="number" class="form-control rule-page" name="category[page]"
                           value="{{$rule_json['bookList']['category']['page']??'1'}}">
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="hr-line-dashed"></div>

          <div class="form-group">
            <label class="col-sm-2 control-label">简介详情</label>
            <div class="col-sm-10">
              <table class="rule-table">
                <thead>
                <tr>
                  <th width="100">Key</th>
                  <th>Rule</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td>title</td>
                  <td>
                    <input class="form-control rule-select" name="home[title][]"
                           placeholder="CSS元素查询器[必填]"
                           value="{{$rule_json['home']['rules']['title'][0]??''}}">
                    <input class="form-control" name="home[title][]" placeholder="属性[必填]"
                           value="{{$rule_json['home']['rules']['title'][1]??''}}">
                    <input class="form-control" name="home[title][]" placeholder="过滤元素[选填]"
                           value="{{$rule_json['home']['rules']['title'][2]??''}}">
                    <input class="form-control" name="home[title][]" placeholder="保留元素[选填]"
                           value="{{$rule_json['home']['rules']['title'][3]??''}}">
                  </td>
                </tr>
                <tr>
                  <td>words_count</td>
                  <td>
                    <input class="form-control rule-select" name="home[words_count][]"
                           placeholder="CSS元素查询器[必填]"
                           value="{{$rule_json['home']['rules']['words_count'][0]??''}}">
                    <input class="form-control" name="home[words_count][]" placeholder="属性[必填]"
                           value="{{$rule_json['home']['rules']['words_count'][1]??''}}">
                    <input class="form-control" name="home[words_count][]" placeholder="过滤元素[选填]"
                           value="{{$rule_json['home']['rules']['words_count'][2]??''}}">
                    <input class="form-control" name="home[words_count][]" placeholder="保留元素[选填]"
                           value="{{$rule_json['home']['rules']['words_count'][3]??''}}">
                  </td>
                </tr>
                <tr>
                  <td>chapter_list_url</td>
                  <td>
                    <input class="form-control rule-select" name="home[chapter_list_url][]"
                           placeholder="CSS元素查询器[必填]"
                           value="{{$rule_json['home']['rules']['chapter_list_url'][0]??''}}">
                    <input class="form-control" name="home[chapter_list_url][]"
                           placeholder="属性[必填]"
                           value="{{$rule_json['home']['rules']['chapter_list_url'][1]??''}}">
                    <input class="form-control" name="home[chapter_list_url][]"
                           placeholder="过滤元素[选填]"
                           value="{{$rule_json['home']['rules']['chapter_list_url'][2]??''}}">
                    <input class="form-control" name="home[chapter_list_url][]"
                           placeholder="保留元素[选填]"
                           value="{{$rule_json['home']['rules']['chapter_list_url'][3]??''}}">
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="hr-line-dashed"></div>

          <div class="form-group">
            <label class="col-sm-2 control-label">目录详情</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="chapterList[range]" placeholder="范围选择器 range"
                     value="{{ $rule_json['chapterList']['range']??'' }}"/>
              <table class="rule-table">
                <thead>
                <tr>
                  <th width="100">Key</th>
                  <th>Rule</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td>title</td>
                  <td>
                    <input class="form-control rule-select" name="chapterList[title][]"
                           placeholder="CSS元素查询器[必填]"
                           value="{{$rule_json['chapterList']['rules']['title'][0]??''}}">
                    <input class="form-control" name="chapterList[title][]" placeholder="属性[必填]"
                           value="{{$rule_json['chapterList']['rules']['title'][1]??''}}">
                    <input class="form-control" name="chapterList[title][]" placeholder="过滤元素[选填]"
                           value="{{$rule_json['chapterList']['rules']['title'][2]??''}}">
                    <input class="form-control" name="chapterList[title][]" placeholder="保留元素[选填]"
                           value="{{$rule_json['chapterList']['rules']['title'][3]??''}}">
                  </td>
                </tr>
                <tr>
                  <td>from_url</td>
                  <td>
                    <input class="form-control rule-select" name="chapterList[from_url][]"
                           placeholder="CSS元素查询器[必填]"
                           value="{{$rule_json['chapterList']['rules']['from_url'][0]??''}}">
                    <input class="form-control" name="chapterList[from_url][]" placeholder="属性[必填]"
                           value="{{$rule_json['chapterList']['rules']['from_url'][1]??''}}">
                    <input class="form-control" name="chapterList[from_url][]"
                           placeholder="过滤元素[选填]"
                           value="{{$rule_json['chapterList']['rules']['from_url'][2]??''}}">
                    <input class="form-control" name="chapterList[from_url][]"
                           placeholder="保留元素[选填]"
                           value="{{$rule_json['chapterList']['rules']['from_url'][3]??''}}">
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="hr-line-dashed"></div>

          <div class="form-group">
            <label class="col-sm-2 control-label">正文详情</label>
            <div class="col-sm-10">
              <table class="rule-table">
                <thead>
                <tr>
                  <th width="100">Key</th>
                  <th>Rule</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td>content</td>
                  <td>
                    <input class="form-control rule-select" name="content[content][]"
                           placeholder="CSS元素查询器[必填]"
                           value="{{$rule_json['content']['rules']['content'][0]??''}}">
                    <input class="form-control" name="content[content][]" placeholder="属性[必填]"
                           value="{{$rule_json['content']['rules']['content'][1]??''}}">
                    <input class="form-control" name="content[content][]" placeholder="过滤元素[选填]"
                           value="{{$rule_json['content']['rules']['content'][2]??''}}">
                    <input class="form-control" name="content[content][]" placeholder="保留元素[选填]"
                           value="{{$rule_json['content']['rules']['content'][3]??''}}">
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="hr-line-dashed"></div>

          <div class="form-group">
            <label class="col-sm-2 control-label">正文截断标记</label>
            <div class="col-sm-10">
              <input class="form-control" name="splitTag" placeholder="正文截断标记，忽略标签后面内容"
                     value="{{$rule_json['splitTag']??''}}">
            </div>
          </div>
          <div class="hr-line-dashed"></div>

          <div class="form-group">
            <label class="col-sm-2 control-label">替换正则</label>
            <div class="col-sm-10">
              <table class="rule-table">
                <thead>
                <tr>
                  <th>正则表达式 => 替换内容</th>
                  <th>操作</th>
                </tr>
                </thead>
                <tbody id="replaceTag">
                @if(!empty($rule_json['replaceTags']))
                  @foreach($rule_json['replaceTags'] as $k => $tag)
                    <tr>
                      <td>
                        <input class="form-control rule-replace" placeholder="正则表达式"
                               name="replaceTags[{{$k}}][]" value="{{$tag[0]??''}}">
                        <input class="form-control" placeholder="替换内容"
                               name="replaceTags[{{$k}}][]" value="{{$tag[1]??''}}">
                      </td>
                      <td>
                        @if($k == 0)
                          <a href="javascript:void(0);" onclick="addReplaceTag()">添加</a>
                        @else
                          <a href="javascript:void(0);" onclick="delReplaceTag()">删除</a>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                @else
                  <tr>
                    <td>
                      <input class="form-control rule-replace" name="replaceTags[0][]"
                             placeholder="正则表达式">
                      <input class="form-control" name="replaceTags[0][]" placeholder="替换内容">
                    </td>
                    <td>
                      <a href="javascript:void(0);" onclick="addReplaceTag()">添加</a>
                    </td>
                  </tr>
                @endif
                </tbody>
              </table>
            </div>
          </div>
          <div class="hr-line-dashed"></div>

          <div class="form-group">
            <div class="col-sm-4 col-sm-offset-2">
              <button class="btn btn-primary" type="submit">保存内容</button>
              <button class="btn btn-info" type="button" onclick="testRule()">测试</button>
              <a class="btn btn-white" href="{{ route('Book.collectionRule') }}">返回</a>
              <input type="hidden" id="test_type" name="test_type" value="">
              <input type="hidden" id="test_url" name="test_url" value="">
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>

  <!-- jQuery Validation plugin javascript-->
  {!! jquery_validate_js() !!}
  <script>
    var testRuleModal = '#testRuleModal';
    $(function () {
      {!! jquery_validate_default() !!}

      $("#sform").validate({
        debug: false,
        rules: {
          title: {
            required: true,
          },
          host: {
            required: true,
          },
        }
      });
    });

    function addReplaceTag() {
      let r = Math.random().toString(10);
      $("#replaceTag").append(
        '<tr>' +
        '    <td>' +
        '        <input class="form-control rule-replace" name="replaceTags[' + r + '][]" placeholder="正则表达式">' +
        '        <input class="form-control" name="replaceTags[' + r + '][]" placeholder="替换内容">' +
        '    </td>' +
        '    <td>' +
        '        <a href="javascript:void(0);" onclick="delReplaceTag(this)">删除</a>' +
        '    </td>' +
        '</tr>');
    }

    function delReplaceTag(thisObj) {
      $(thisObj).parents('tr').remove();
    }

    function testRule() {
      $('#msg').html("");
      $(testRuleModal).modal('show');
    }

    function doTestList() {
      let url = $('#test_category_url').val();
      if (url === '') {
        layer.alert('URL 不能为空');
        return false;
      }
      var msg = $('#msg');
      msg.html("<li>正在请求中...</li>");
      $('#test_type').val('category');
      $('#test_url').val(url);
      $.ajax({
        url: "{{route('Book.testCollectionRule')}}",
        type: 'post',
        data: $('#sform').serialize(),
        dataType: 'json',
        success: function (res) {
          msg.prepend("<li>" + JSON.stringify(res.data) + "</li>");
        }
      })
    }

    function doTestHome() {
      let url = $('#test_home_url').val();
      if (url === '') {
        layer.alert('URL 不能为空');
        return false;
      }
      var msg = $('#msg');
      msg.html("<li>正在请求中...</li>");
      $('#test_type').val('home');
      $('#test_url').val(url);
      $.ajax({
        url: "{{route('Book.testCollectionRule')}}",
        type: 'post',
        data: $('#sform').serialize(),
        dataType: 'json',
        success: function (res) {
          msg.prepend("<li>" + JSON.stringify(res.data) + "</li>");
          doGetContent(res.data.chapter_list_url);
        }
      })
    }

    function doGetContent(url) {
      var msg = $('#msg');
      msg.prepend("<li>获取某随机章节正文...</li>");
      $('#test_type').val('content');
      $('#test_url').val(url);
      $.ajax({
        url: "{{route('Book.testCollectionRule')}}",
        type: 'post',
        data: $('#sform').serialize(),
        dataType: 'json',
        success: function (res) {
          msg.prepend("<li>" + JSON.stringify(res.data) + "</li>");
        }
      })
    }
  </script>

  <div class="modal" id="testRuleModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content animated flipInX">
        <form action="#" method="POST" class="form-horizontal">
          {!! csrf_field() !!}
          <input type="hidden" name="id" value="">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
            </button>
            <h4 class="modal-title">测试规则</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-2 control-label">分类/排行URL</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="test_category_url" value=""
                       placeholder="http://xxx.com/xxx/{$page}.html">
                <span class="help-block m-b-none">{$page}表示页码</span>
              </div>
              <div class="col-sm-2">
                <button type="button" class="btn btn-primary" onclick="doTestList()">测试</button>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">简介URL</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="test_home_url"
                       value=""
                       placeholder="http://xxx.com/xxx">
              </div>
              <div class="col-sm-2">
                <button type="button" class="btn btn-primary" onclick="doTestHome()">测试</button>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">结果</label>
              <div class="col-sm-10">
                <ul id="msg" style="display: block; height: 100px; overflow-y: auto;"></ul>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection('content')
