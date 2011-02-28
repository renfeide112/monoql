Ext.ns('monoql.panel');
monoql.panel.viewport = function() {
	var cls = 'monoql-panel-viewport';
	var Class = Ext.extend(Ext.Viewport, {
		layout:'border',
		border:false,
		initComponent: function() {
			this.toolbar = new monoql.bar.maintoolbar({
				region:'north'
			});
			this.tabs = new monoql.tab.maintabset({
				region:'center'
			});
			this.tree = new monoql.tree.connectiontree({
				region:'west',
				split:true
			});
			this.connectionform = new monoql.form.connectionform({
				hidden:true
			});
			this.addquerytabform = new monoql.form.addquerytabform({
				hidden:true
			});
			this.items = [this.toolbar, this.tree, this.tabs];
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();