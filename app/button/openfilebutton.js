Ext.ns('monoql.button');
monoql.button.openfilebutton = function() {
	var cls = 'monoql-button-openfilebutton';
	var Class = Ext.extend(monoql.button.button, {
		iconCls:cls + '-icon',
		tooltip:'Open a query file',
		initComponent: function() {
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();