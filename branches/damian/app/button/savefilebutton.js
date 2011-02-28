Ext.ns('monoql.button');
monoql.button.savefilebutton = function() {
	var cls = 'monoql-button-savefilebutton';
	var Class = Ext.extend(monoql.button.button, {
		iconCls:cls + '-icon',
		disabled:true,
		tooltip:'Save active query to file',
		initComponent: function() {
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();