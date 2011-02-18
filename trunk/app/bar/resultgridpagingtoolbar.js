Ext.ns('monoql.bar');
monoql.bar.resultgridpagingtoolbar = function() {
	var cls = 'monoql-bar-resultgridpagingtoolbar';
	var Class = Ext.extend(Ext.PagingToolbar, {
		initComponent: function() {
			Ext.apply(this, {
				store:this.grid.store
			});
			Class.superclass.initComponent.call(this);
			this.grid.store.on('metachange', this.onGridStoreMetaChange, this);
			this.addClass(cls);
		},
		onGridStoreMetaChange:function(store, meta) {
			// may need to do something here when the store reader has metadata changed... not sure yet
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();