Ext.ns('monoql.tab');
monoql.tab.tabletab = function() {
	var cls = 'monoql-tab-tabletab';
	var Class = Ext.extend(monoql.tab.tab, {
		layout:'fit',
		border:false,
		initComponent: function() {
			this.title = (this.database || 'Unknown') + '.' + (this.table || 'Unknown');
			this.grid = new monoql.grid.tablegrid({
				tab:this
			});
			this.grid.on('render', this.onTableGridRender, this);
			this.items = [this.grid];
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		onTableGridRender:function(grid) {
			this.grid.getStore().load({
				params:{
					table:this.table,
					connectionId:this.connection.id,
					database:this.database
				}
			});
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();