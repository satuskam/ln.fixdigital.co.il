<div class="shfs_meta_control">
    <p>Add some code to <code>&lt;wordpress footer&gt;</code>.</p>
    
    <p>
        <textarea name="_inpost_footer_script[code]" rows="5" style="width:98%;"><?php if(!empty($footerScriptMeta['code'])) echo $footerScriptMeta['code']; ?></textarea>
    </p>
    
    <p>
        <input type="checkbox" name="_inpost_footer_script[is_active]" value="1" <?= $footerScriptMeta['is_active'] ? 'checked="checked"' : ''  ?> > Is Active
    </p>
</div>