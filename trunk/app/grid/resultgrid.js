Ext.ns('monoql.grid');
monoql.grid.resultgrid = function() {
	var cls = 'monoql-grid-resultgrid';
	var Class = Ext.extend(monoql.grid.grid, {
		border:false,
		initComponent: function() {
			this.store = new monoql.data.resultgridstore({grid:this});
			Ext.apply(this, {
				colModel:new monoql.grid.resultgridcolumnmodel({grid:this}),
				selModel:new monoql.grid.resultgridselectionmodel({grid:this}),
				view:new monoql.grid.resultgridview({grid:this}),
				bbar:new monoql.bar.resultgridpagingtoolbar({grid:this})
			});
			this.on('render', this.onResultGridRender, this);
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		onResultGridRender:function(grid) {
			this.getQueryTab().queryform.on('query', this.onQueryFormQuery, this);
		},
		onQueryFormQuery:function(queryform, query, connection) {
			this.getStore().removeAll();
		},
		getQueryTab:function() {
			return this.findParentByType(monoql.tab.querytab);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();