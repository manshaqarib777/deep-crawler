<?php //if(in_array(2,$this->role_module_accesses_2)): ?>
<a class="btn btn-warning"  title="Add Book" href="<?php echo site_url('admin/add_book');?>">
    <i class="fa fa-plus-circle"></i> Add
</a>
<a class="btn btn-warning pull-right" id="print_barcode" onClick="PrintElem('#for_barcode_display')" title="Print Catalog" style="display:none;margin-left:5px;">
    <i class="fa fa-print"></i> Print Catalog
</a>

<a class="btn btn-success pull-right" id="barcode"  title="Generate Catalog">
    <i class="fa fa-barcode"></i> Generate Catalog
</a>

<div id="for_barcode_display" style="display:none;">
</div>
<?php //endif; ?>