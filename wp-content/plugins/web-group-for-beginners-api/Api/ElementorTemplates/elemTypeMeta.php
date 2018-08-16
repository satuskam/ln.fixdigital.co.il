<div class="shfs_meta_control">
    <p>
        Select template custom type:
        <select name="_elementor_template_custom_type">
            <?php foreach (['' => '------', 'header' => 'Header', 'footer' => 'Footer', 'sidebar' => 'Sidebar'] as $opt => $name) : ?>
                <option value="<?= $opt ?>" <?= $elemTypeMeta === $opt ? 'selected' : '' ?> ><?= $name ?></option>
            <?php endforeach; ?>
        </select>
    </p>
</div>