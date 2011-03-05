Ext.ns('monoql.button');
monoql.button.button = function() {
	var cls = 'monoql-button-button';
	var Class = Ext.extend(Ext.Button, {
		initComponent: function() {
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();