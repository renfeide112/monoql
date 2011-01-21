Ext.ns('monoql.form');
monoql.form.addquerytabform = function() {
	var cls = 'monoql-form-addquerytabform';
	
	var OpenTabButton = Ext.extend(monoql.button.button, {
		text:'Open Tab',
		initComponent:function() {
		OpenTabButton.superclass.initComponent.call(this);
		}
	});
	
	var Class = Ext.extend(monoql.form.floatingform, {
		title:'Add a new query tab',
		width:200,
		initComponent: function() {
			this.opentabbutton = new OpenTabButton();
			this.connectioncombobox = new monoql.form.connectioncombobox({
				fieldLabel:'Choose a connection'
			});
			this.items = [this.connectioncombobox];
			this.buttons = [this.opentabbutton];
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();