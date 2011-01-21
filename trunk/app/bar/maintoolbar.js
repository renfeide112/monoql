Ext.ns('monoql.bar');
monoql.bar.maintoolbar = function() {
	var cls = 'monoql-bar-maintoolbar';
	var Class = Ext.extend(monoql.bar.toolbar, {
		height:26,
		initComponent: function() {
			this.filemenu = new monoql.menu.filemenu();
			this.editmenu = new monoql.menu.editmenu();
			this.toolsmenu = new monoql.menu.toolsmenu();
			this.datamenu = new monoql.menu.datamenu();
			this.helpmenu = new monoql.menu.helpmenu();
			this.openfilebutton = new monoql.button.openfilebutton();
			this.savefilebutton = new monoql.button.savefilebutton();
			this.runquerybutton = new monoql.button.runquerybutton();
			this.cancelquerybutton = new monoql.button.cancelquerybutton();
			this.addquerytabbutton = new monoql.button.addquerytabbutton();
			this.resultstogridbutton = new monoql.button.resultstogridbutton();
			this.resultstotextbutton = new monoql.button.resultstotextbutton();
			this.resultstofilebutton = new monoql.button.resultstofilebutton();
			this.connectioncombobox = new monoql.form.connectioncombobox();
			this.databasecombobox = new monoql.form.databasecombobox();
			this.items = [{
				text:'File',
				menu:this.filemenu
			}, {
				text:'Edit',
				menu:this.editmenu
			}, {
				text:'Tools',
				menu:this.toolsmenu
			}, {
				text:'Data',
				menu:this.datamenu
			}, {
				text:'Help',
				menu:this.helpmenu
			},
				'-', 
				this.openfilebutton, 
				this.savefilebutton,
				'-', 
				this.runquerybutton, 
				this.cancelquerybutton,
				this.addquerytabbutton,
				'-', 
				this.resultstogridbutton, 
				this.resultstotextbutton, 
				this.resultstofilebutton,
				'-',
				this.connectioncombobox,
				'-',
				this.databasecombobox
			];
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();