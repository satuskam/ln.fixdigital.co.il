<div class="shfs_meta_control">
    <p>Add some code to <code>&lt;head&gt;</code>.</p>
    
    <p>
        <textarea name="_inpost_head_script[code]" rows="5" style="width:98%;"><?php if(!empty($headerScriptMeta['code'])) echo $headerScriptMeta['code']; ?></textarea>
    </p>
    
    <p>
        <input type="checkbox" name="_inpost_head_script[is_active]" value="1" <?= $headerScriptMeta['is_active'] ? 'checked="checked"' : ''  ?> > Is Active
    </p>
</div>