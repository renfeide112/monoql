Ext.ns('monoql.button');
monoql.button.resultstofilebutton = function() {
	var cls = 'monoql-button-resultstofilebutton';
	var Class = Ext.extend(monoql.button.button, {
		iconCls:cls + '-icon',
		tooltip:'Save query results to file',
		enableToggle:true,
		toggleGroup:'monoql-button-resultstogglegroup',
		initComponent: function() {
			this.on('click', this.onResultsToFileButtonClick, this);
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		onResultsToFileButtonClick:function(button, e) {
			if (!this.pressed) {
				this.toggle(true);
			}
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();