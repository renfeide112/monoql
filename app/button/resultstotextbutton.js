Ext.ns('monoql.button');
monoql.button.resultstotextbutton = function() {
	var cls = 'monoql-button-resultstotextbutton';
	var Class = Ext.extend(monoql.button.button, {
		iconCls:cls + '-icon',
		tooltip:'Display query results as text',
		enableToggle:true,
		toggleGroup:'monoql-button-resultstogglegroup',
		initComponent: function() {
			this.on('click', this.onResultsToTextButtonClick, this);
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		onResultsToTextButtonClick:function(button, e) {
			if (!this.pressed) {
				this.toggle(true);
			}
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();