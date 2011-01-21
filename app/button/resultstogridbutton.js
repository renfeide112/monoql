Ext.ns('monoql.button');
monoql.button.resultstogridbutton = function() {
	var cls = 'monoql-button-resultstogridbutton';
	var Class = Ext.extend(monoql.button.button, {
		iconCls:cls + '-icon',
		tooltip:'Display query results as grid',
		enableToggle:true,
		toggleGroup:'monoql-button-resultstogglegroup',
		pressed:true,
		initComponent: function() {
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();