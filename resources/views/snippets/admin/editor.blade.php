<script src="/libs/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    var editor = CKEDITOR;
    editor.config.imagePatch = '{{$path}}';
    editor.replace('editor');
    editor.resize('100%', '500', true);
</script>
