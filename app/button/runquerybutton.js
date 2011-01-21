Ext.ns('monoql.button');
monoql.button.runquerybutton = function() {
	var cls = 'monoql-button-runquerybutton';
	var Class = Ext.extend(monoql.button.button, {
		iconCls:cls + '-icon',
		disabled:true,
		tooltip:'Execute active query',
		initComponent: function() {
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();