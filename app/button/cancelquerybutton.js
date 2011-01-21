Ext.ns('monoql.button');
monoql.button.cancelquerybutton = function() {
	var cls = 'monoql-button-cancelquerybutton';
	var Class = Ext.extend(monoql.button.button, {
		iconCls:cls + '-icon',
		disabled:true,
		tooltip:'Cancel active query',
		initComponent: function() {
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();