Ext.ns('monoql.grid');
monoql.grid.resultgridcolumnmodel = function() {
	var Class = Ext.extend(Ext.grid.ColumnModel, {
		constructor:function(config) {
			Ext.apply(this, {
				// The column model will be configured at runtime by listening
				// for the metachange event on the grid store
			}, config);
			Class.superclass.constructor.call(this, config);
			this.grid.store.on('metachange', this.onGridStoreMetaChange, this);
		},
		onGridStoreMetaChange:function(store, meta) {
			var columns = [];
			Ext.each(meta.fields, function(item, index, items) {
				columns.push({header:item.name, dataIndex:item.name});
			});
			// Only takes an array, not config object... ext bug?
			this.setConfig(columns);
		}
	});
	return Class;
}();