Ext.ns('monoql.grid');
monoql.grid.tablegridcolumnmodel = Ext.extend(Ext.grid.ColumnModel, {
	constructor:function(config) {
		Ext.apply(this, {
			defaults:{
				sortable:true
			}
		}, config);
		monoql.grid.tablegridcolumnmodel.superclass.constructor.call(this, config);
		this.grid.store.on('metachange', this.onGridStoreMetaChange, this);
	},
	onGridStoreMetaChange:function(store, meta) {
		var columns = [];
		Ext.each(meta.fields, function(item, index, items) {
			if (item.name !== "__id__") {
				columns.push({header:item.name, dataIndex:item.name});
			}
		});
		this.setConfig(columns);
	}
});

monoql.grid.tablegridselectionmodel = Ext.extend(Ext.ux.grid.livegrid.RowSelectionModel, {
	constructor:function(config) {
		config = Ext.apply({
		}, config);
		monoql.grid.tablegridselectionmodel.superclass.constructor.call(this, config);
	}
});

monoql.grid.tablegridview = Ext.extend(Ext.ux.grid.livegrid.GridView, {
	constructor:function(config) {
		config = Ext.apply({
			nearLimit:20,
			loadMask:{
				msg:'Loading rows...'
			}
		}, config);
		monoql.grid.tablegridview.superclass.constructor.call(this, config);
	}
});
	
monoql.grid.tablegrid = function() {
	var cls = 'monoql-grid-tablegrid';
	var Class = Ext.extend(monoql.grid.livegrid, {
		border:false,
		stripeRows:true,
		initComponent: function() {
			this.store = new monoql.data.tablegridstore({grid:this});
			Ext.apply(this, {
				colModel:new monoql.grid.tablegridcolumnmodel({grid:this}),
				selModel:new monoql.grid.tablegridselectionmodel({grid:this}),
				view:new monoql.grid.tablegridview({grid:this})
			});
			Ext.apply(this, {
				bbar:new monoql.bar.tablegridpagingtoolbar({grid:this})
			});
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();