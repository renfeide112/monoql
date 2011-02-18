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
			// call this.setConfig() and the grid/gridview will update automatically;
			alert('TODO: Configure resultgridcolumnmodel to reconfigure on grid store metachange');
		}
	});
	return Class;
}();