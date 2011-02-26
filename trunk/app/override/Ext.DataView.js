Ext.override(Ext.DataView, {
    onUpdate : function(ds, record){
        var index = this.store.indexOf(record);
        if(index > -1){
            var sel = this.isSelected(index),
                original = this.all.elements[index],
                node = this.bufferRender([record], index)[0];

            this.all.replaceElement(index, node, true);
            if(sel){
                this.selected.replaceElement(original, node);
                this.all.item(index).addClass(this.selectedClass);
            }
            this.updateIndexes(index, index);
        }
    }
});