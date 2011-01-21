<?php
class Generator {

	public static $tables = array();
	public static $classes = array();
	public static $linesOfCode = 0;
	public static $site = null;
	public static $types = array(
		"BIT" => 		array("mssql"=>"BIT","oracle"=>"NUMBER(1)","sqlite"=>"BIT"),
		"TINYINT" => 	array("mssql"=>"TINYINT","oracle"=>"NUMBER(38)","sqlite"=>"TINYINT"),
		"SMALLINT" => 	array("mssql"=>"SMALLINT","oracle"=>"NUMBER(38)","sqlite"=>"SMALLINT"),
		"MEDIUMINT" => 	array("mssql"=>"INT","oracle"=>"NUMBER(38)","sqlite"=>"MEDIUMINT"),
		"INT" => 		array("mssql"=>"INT","oracle"=>"NUMBER(38)","sqlite"=>"INT"),
		"INTEGER" => 	array("mssql"=>"INT","oracle"=>"NUMBER(38)","sqlite"=>"INTEGER"),
		"BIGINT" => 	array("mssql"=>"BIGINT","oracle"=>"NUMBER(38)","sqlite"=>"BIGINT"),
		"REAL" => 		array("mssql"=>"FLOAT(53)","oracle"=>"FLOAT(63)","sqlite"=>"REAL"),
		"DOUBLE" => 	array("mssql"=>"FLOAT(53)","oracle"=>"FLOAT(126)","sqlite"=>"DOUBLE"),
		"FLOAT" => 		array("mssql"=>"FLOAT","oracle"=>"FLOAT","sqlite"=>"FLOAT"),
		"DECIMAL" => 	array("mssql"=>"DECIMAL","oracle"=>"NUMBER","sqlite"=>"DECIMAL"),
		"NUMERIC" => 	array("mssql"=>"NUMERIC","oracle"=>"NUMBER","sqlite"=>"NUMERIC"),
		"DATE" => 		array("mssql"=>"DATETIME","oracle"=>"DATE","sqlite"=>"DATE"),
		"TIME" => 		array("mssql"=>"DATETIME","oracle"=>"DATE","sqlite"=>"TIME"),
		"TIMESTAMP" => 	array("mssql"=>"DATETIME","oracle"=>"TIMESTAMP","sqlite"=>"TIMESTAMP"),
		"DATETIME" => 	array("mssql"=>"DATETIME","oracle"=>"DATE","sqlite"=>"DATETIME"),
		"YEAR" => 		array("mssql"=>"INT","oracle"=>"NUMBER(4)","sqlite"=>"YEAR"),
		"CHAR" => 		array("mssql"=>"CHAR","oracle"=>"CHAR","sqlite"=>"CHAR"),
		"VARCHAR" => 	array("mssql"=>"VARCHAR","oracle"=>"VARCHAR2","sqlite"=>"VARCHAR"),
		"BINARY" => 	array("mssql"=>"BINARY","oracle"=>"RAW","sqlite"=>"BINARY"),
		"VARBINARY" => 	array("mssql"=>"VARBINARY","oracle"=>"BLOB","sqlite"=>"VARBINARY"),
		"TINYBLOB" => 	array("mssql"=>"VARBINARY","oracle"=>"BLOB","sqlite"=>"TINYBLOB"),
		"BLOB" => 		array("mssql"=>"VARBINARY(MAX)","oracle"=>"BLOB","sqlite"=>"BLOB"),
		"MEDIUMBLOB" => array("mssql"=>"VARBINARY(MAX)","oracle"=>"BLOB","sqlite"=>"MEDIUMBLOB"),
		"LONGBLOB" => 	array("mssql"=>"VARBINARY(MAX)","oracle"=>"BLOB","sqlite"=>"LONGBLOB"),
		"TINYTEXT" => 	array("mssql"=>"VARCHAR","oracle"=>"CLOB","sqlite"=>"TINYTEXT"),
		"TEXT" => 		array("mssql"=>"VARCHAR(MAX)","oracle"=>"CLOB","sqlite"=>"TEXT"),
		"MEDIUMTEXT" => array("mssql"=>"VARCHAR(MAX)","oracle"=>"CLOB","sqlite"=>"MEDIUMTEXT"),
		"LONGTEXT" => 	array("mssql"=>"VARCHAR(MAX)","oracle"=>"CLOB","sqlite"=>"LONGTEXT"),
		"ENUM" => 		array("mssql"=>"INT","oracle"=>"NUMBER","sqlite"=>"INT"),
		"SET" => 		array("mssql"=>"INT","oracle"=>"NUMBER","sqlite"=>"INT")
	);
	
	public static function generate($site=null) {
		self::$site = $site;
		self::$tables = array();
		self::$classes = array();
		self::$linesOfCode = 0;
		
		debug("Making temp folder...");
		self::makeFolder(Helix::$path . "/temp");
		
		debug("Importing module schema definition files...");
		self::importModules();
		
		if (isset($site) && strlen($site)>0) {
			debug("Importing site schema definition file for: {$site}...");
			self::importSite($site);
		}
		
		debug("Parsing schema definition files...");
		self::parseTables();
		self::parseTablesAgain();
		
		debug("Writing class files...");
		self::writeClasses();
		
		debug("Updating Helix class paths...");
		self::setHelixClassPaths();
		
		if (isset($site) && strlen($site)>0) {
			debug("Updating site class paths for: {$site}...");
			self::setSiteClassPaths();
		}
		
		debug("Writing MySQL database schema definition...");
		self::writeMySQL();
		
		debug("Writing MySQL log database schema definition...");
		self::writeMySQL(true);
		
		debug("Writing MySQL insert statements...");
		self::writeMySQLInserts();
		
		debug("Generator Done: " . count(self::$tables) . " tables generated");
	}
	
	public static function setHelixClassPaths($folder=null) {
		$files = array();
		$paths = isset($folder) ? glob("{$folder}/*") : glob(Helix::$path . "/*");
		
		foreach ($paths as $path) {
			if (in_array(strrchr($path, "/"), array("/External", "/Resources", "/Applications"))) {continue;}
			if (is_file($path) && strrchr($path,".")===".php") {
				$files[basename($path)] = preg_replace('/^' . preg_quote(Helix::$path,"/") . '[\\\\\\/]*(.*)$/i','$1',$path);
			} else if (is_dir($path)) {
				$files = array_merge($files, self::setHelixClassPaths($path));
			}
		}
		ksort($files);
		
		$lines = array("{");
		foreach ($files as $file=>$path) {
			$class = basename($file, ".php");
			$lines[] = TAB . json_encode($class) . ":\"" . str_replace("\\", "/", $path) . "\",";
		}
		$lines[count($lines)-1] = substr($lines[count($lines)-1], 0, -1);
		$lines[] = "}";
		$text = implode(NL, $lines);
		file_put_contents(Helix::$path . "/config/helix.classes.json", $text);
		
		return $files;
	}
	
	public static function setSiteClassPaths($folder=null) {
		$site = self::$site;
		$files = array();
		$baseFolder = dirname(realpath("."));
		$paths = isset($folder) ? glob("{$folder}/*") : glob("{$baseFolder}/{$site}/library/*");
		
		foreach ($paths as $path) {
			if (strrchr($path, "/")==="/External" || strrchr($path, "/")==="/Resources") {continue;}
			if (is_file($path) && strrchr($path,".")===".php") {
				$files[basename($path)] = preg_replace('/^' . preg_quote("{$baseFolder}/{$site}/","/") . '[\\\\\\/]*(.*)$/i','$1',$path);
			} else if (is_dir($path)) {
				$files = array_merge($files, self::setSiteClassPaths($path));
			}
		}
		ksort($files);
		
		$lines = array("{");
		foreach ($files as $file=>$path) {
			$class = basename($file, ".php");
			$lines[] = TAB . json_encode($class) . ":\"" . str_replace("\\", "/", $path) . "\",";
		}
		$lines[count($lines)-1] = substr($lines[count($lines)-1], 0, -1);
		$lines[] = "}";
		$text = implode(NL, $lines);
		file_put_contents("{$baseFolder}/{$site}/library/Config/{$site}.classes.json", $text);
		
		return $files;
	}
	
	public static function importJSON($path, $module=null, $site=null) {
		$schema = json_decode(file_get_contents($path), true);
		$data = json_decode(file_get_contents(str_replace("schema.json","data.json",$path)), true);
		foreach ($schema["tables"] as $name=>$table) {
			$table["module"] = $module;
			$table["site"] = $site;
			if (array_key_exists("tables", $data) && array_key_exists($name, $data["tables"])) {
				$table["data"] = $data["tables"][$name]["data"];
			}
			self::$tables[$name] = $table;
		}
	}
	
	public static function makeFolder($path) {
		if (!file_exists($path)) {
			mkdir($path, 0755, true);
		}
	}
	
	public static function emptyFolder($path, $deleteFolders=false) {
		if (is_dir($path)) {
			foreach (glob($path . "/*") as $file) {
				if (is_dir($file)) {
					self::emptyFolder($file, $deleteFolders);
					if ($deleteFolders) {
						rmdir($file);
					}
				} else {
					unlink($file);
				}
			}
		}
	}
	
	public static function importModules() {
		foreach (glob(Helix::$path . "/modules/*/Config/*.schema.json") as $path) {
			$module = basename(dirname(dirname($path)));
			self::importJSON($path, $module, null);
			self::emptyFolder(dirname(dirname($path)) . "/Generated");
		}
	}
	
	public static function importSite($site) {
		$path = dirname(realpath(".")) . "/{$site}/library/Config/{$site}.schema.json";
		self::importJSON($path, null, $site);
		self::emptyFolder(dirname(dirname($path)) . "/Generated");
	}
	
	public static function parseTables() {
		foreach (self::$tables as $tablename=>&$table) {
			$table["name"] = $tablename;
			$table["parts"] = explode("_",$tablename);
			$table["class"] = str_replace(" ","",ucwords(str_replace("_"," ",strtolower($tablename))));
			$table["has_type"] = array_key_exists("{$tablename}_type",self::$tables);
			$table["is_type"] = strrchr($tablename,"_")==="_type";
			$table["type"] = self::getType($table);
			$table["is_ordered"] = array_key_exists("order",$table["columns"]);
			$table["is_self_referential"] = count($table["parts"])===2 && $table["parts"][0]===$table["parts"][1];
			$table["is_child"] = array_key_exists("_id",$table["columns"]);
			$table["is_parent"] = false;
			$table["is_column_extension"] = count($table["columns"])===6 && count(array_diff(array_keys($table["columns"]), array("id","value","updated_by_id","mdate","cdate","deleted")))===0;
			if ($table["is_self_referential"]) {
				self::$tables[$table["parts"][0]]["is_self_linked"] = true;
			}
			$table["is_self_linked"] = isset($table["is_self_linked"]) ? $table["is_self_linked"] : false;
			foreach ($table["columns"] as $colname=>&$column) {
				$column["name"] = $colname;
				$column["table"] = $tablename;
				$column["php_type"] = self::getPHPType($column["type"]);
				if ($table["is_self_referential"] && preg_match('/^(child|parent)_' . $table["parts"][0] . '_id$/', $colname)) {
					$column["property"] = camel(str_replace("_{$table["parts"][0]}_", "_", $colname));
				} else {
					$column["property"] = camel($colname);
				}
				
				if (val($column,"key")==="primary") {
					$column["is_primary"] = true;
					$table["primary_keys"][] = $colname;
				} else {
					$column["is_primary"] = false;
				}
				
				if (val($column,"key")==="unique") {
					$column["is_unique"] = true;
					$table["unique_keys"][] = $colname;
				} else if (is_array(val($column,"key")) && $column["key"][0]==="unique") {
					$column["is_unique"] = true;
					$table["unique_keys"][] = array($colname, $column["key"][1]);
				} else {
					$column["is_unique"] = false;
				}
				$column["auto"] = val($column,"auto")===true;
				$column["default"] = val($column,"default");
				if (array_key_exists("references",$column)) {
					$reftable = $column["references"][0];
					$refcol = $column["references"][1];
					$niceref = $table["is_self_referential"] && preg_match('/^(parent|child).*$/i', $colname) ? preg_replace('/^(parent|child).*$/i','$1',$colname) : $reftable;
					$table["references"][$niceref] = array("local"=>$colname, "foreign"=>$refcol);
					self::$tables[$reftable]["referenced_by"][$tablename] = array("local"=>$refcol, "foreign"=>$colname);
					if ($table["type"]==="RELATIONSHIP" && strrchr($reftable,"_")!=="_type") {
						$table["related"][] = array("table"=>$reftable,"column"=>$refcol,"reltable"=>$tablename,"relcol"=>$colname);
					}
					if ($table["type"]==="STANDARD" && val($column,"key")==="primary") {
						$table["parent"] = $reftable; 
						self::$tables[$reftable]["is_parent"] = true;
						self::$tables[$reftable]["children"][] = $tablename;
						foreach (self::$tables[$reftable]["children"] as $child) {
							$other = array_diff(self::$tables[$reftable]["children"],array($child));
							if (count($other)>0) {
								self::$tables[$child]["siblings"] = $other;
							}
						}
					}
				}
			}
			if ($table["type"]==="RELATIONSHIP") {
				self::$tables[$table["related"][0]["table"]]["referenced_by"][$tablename]["linked_table"] = $table["related"][1]["table"];
				self::$tables[$table["related"][1]["table"]]["referenced_by"][$tablename]["linked_table"] = $table["related"][0]["table"];
				self::$tables[$table["related"][0]["table"]]["linked"][$table["related"][1]["table"]] = array(
					"local"=>array($table["related"][0]["table"], $table["related"][0]["column"]),
					"linked"=>array($table["related"][1]["table"], $table["related"][1]["column"]),
					"relationship"=>array("table"=>$table["related"][0]["reltable"], "local"=>$table["related"][0]["relcol"], "linked"=>$table["related"][1]["relcol"])
				);
				self::$tables[$table["related"][1]["table"]]["linked"][$table["related"][0]["table"]] = array(
					"local"=>array($table["related"][1]["table"], $table["related"][1]["column"]),
					"linked"=>array($table["related"][0]["table"], $table["related"][0]["column"]),
					"relationship"=>array("table"=>$table["related"][1]["reltable"], "local"=>$table["related"][1]["relcol"], "linked"=>$table["related"][0]["relcol"])
				);
			}
			$table["collapsed_columns"] = $table["columns"];
			$table["collapsed_primary_keys"] = $table["primary_keys"];
			$table["collapsed_unique_keys"] = isset($table["unique_keys"]) ? $table["unique_keys"] : array();
			$table["primary_key"] = implode(",",$table["primary_keys"]);
			$table["id_column"] = $table["type"]==="RELATIONSHIP" ? "id" : $table["primary_key"];
		}
	}
	
	public static function parseTablesAgain() {
		foreach (self::$tables as &$table) {
			if ($table["type"]==="RELATIONSHIP") {
				foreach ($table["references"] as $reftable=>$refdata) {
					if ($table["is_self_referential"] && ($reftable==="child" || $reftable==="parent")) {
						$reftablename = $table["parts"][0];
						$refParam = "\$" . $reftable . "Id";
					} else {
						$ref = self::$tables[$reftable];
						$reftablename = $reftable;
						if ($ref["type"]!=="STANDARD" && $ref["type"]!=="RELATIONSHIP") {continue;}
						$refParam = "\$" . lcfirst(self::$tables[$reftable]["class"]) . "Id";
					}
					$params[] = array("table"=>$reftablename, "column"=>$refdata["local"], "param"=>$refParam);
				}
				ksort($params);
				$table["params"] = $params;
				unset($params);
			}
			
			if ($table["type"]==="TYPE" || $table["type"]==="RELATIONSHIP_TYPE") {
				$table["is_lookup"] = true;
			} else if (isset($table["referenced_by"])) {
				$isReferencedByPrimaryKey = false;
				foreach ($table["referenced_by"] as $refbytablename=>$refbydata) {
					$refbytable = self::$tables[$refbytablename];
					$isReferencedByPrimaryKey = $isReferencedByPrimaryKey || in_array($refbydata["foreign"], $refbytable["primary_keys"]);
				}
				$table["is_lookup"] = !$isReferencedByPrimaryKey;
			} else {
				$table["is_lookup"] = false;
			}
			
			if ($table["is_lookup"]) {
				if (isset($table["data"])) {
					if (isset($table["data"]["values"])) {
						if (count($table["columns"])===7 && array_key_exists("name", $table["columns"]) && array_key_exists("description", $table["columns"])) {
							if (count($table["data"]["values"])===0) {
								$table["data"]["columns"] = array("id", "name", "description");
								$table["data"]["values"][0] = array("1", "default", "Default");
							}
						}
					} else {
						if (count($table["columns"])===7 && array_key_exists("name", $table["columns"]) && array_key_exists("description", $table["columns"])) {
							$table["data"]["columns"] = array("id", "name", "description");
							$table["data"]["values"][0] = array("1", "default", "Default");
						}
					}
					foreach ($table["data"]["values"] as $i=>&$values) {
						if ($i>0) {
							$values[0] = $i+1;
						}
					}
				} else {
					if (count($table["columns"])===7 && array_key_exists("name", $table["columns"]) && array_key_exists("description", $table["columns"])) {
						$table["data"]["columns"] = array("id", "name", "description");
						$table["data"]["values"][0] = array("1", "default", "Default");
					}
				}
			}
			
			$child = $table;
			while ($child["is_child"]) {
				$parent = self::$tables[$child["parent"]];
				$columns = array();
				foreach ($parent["columns"] as $colname=>&$column) {
					if (!array_key_exists($colname,$child["columns"])) {
						$columns[$colname] = $column;
					}
				}
				$table["collapsed_primary_keys"] = array_merge($parent["primary_keys"], $table["collapsed_primary_keys"]);
				if (isset($parent["unique_keys"])) {
					$table["collapsed_unique_keys"] = array_merge($parent["unique_keys"], $table["collapsed_unique_keys"]);
				}
				$table["collapsed_columns"] = array_merge($columns, $table["collapsed_columns"]);
				$child = $parent;
			}
			
			$niceColumns = array();
			$insertColumns = $table["type"]==="RELATIONSHIP" ? array() : array($table["primary_key"]=>$table["columns"][$table["primary_key"]]);
			$niceCollapsedColumns = array();
			foreach ($table["collapsed_columns"] as $colname=>$column) {
				if ($colname==="_id" || ($table["type"]!=="RELATIONSHIP" && $colname!=="id" && in_array($colname,$table["collapsed_primary_keys"]))) {continue;}
				if ($colname==="id" || isset($table["columns"][$colname])) {
					$niceColumns[$colname] = $column;
				}
				if ($colname!=="_id" && isset($table["columns"][$colname])) {
					$insertColumns[$colname] = $column;
				}
				$niceCollapsedColumns[$colname] = $column;
			}
			$table["nice_columns"] = $niceColumns;
			$table["insert_columns"] = $insertColumns;
			$table["nice_collapsed_columns"] = $niceCollapsedColumns;
		
			if ($table["type"]==="RELATIONSHIP") {
				$table["folder"] = "Relationships";
			} else if ($table["type"]==="RELATIONSHIP_TYPE") {
				$table["folder"] = "Relationships/Types";
			} else if ($table["type"]==="TYPE") {
				$table["folder"] = "Objects/Types";
			} else if ($table["is_lookup"]) {
				$table["folder"] = "Objects/Lookups";
			} else if ($table["is_column_extension"]) {
				$table["folder"] = "Objects/ColumnExtensions";
			} else {
				$table["folder"] = "Objects";
			}
			
			ksort($table);
		}
		
		ksort(self::$tables);
		file_put_contents(Helix::$path . "/temp/generator.txt", print_r(self::$tables, true));
	}
	
	public static function writeClasses() {
		$i = 0;
		foreach (self::$tables as $tablename=>$table) {
			debug("[" . String::lpad(++$i, 3, 0) . "] Writing classes for: {$tablename}" . (isset($table["linked"]) ? " [" . implode(", ", array_keys($table["linked"])) . "]" : ""));
			$moduleFolder = isset($table["module"]) ? Helix::$path . "/modules/{$table["module"]}" : dirname(realpath(".")) . "/{$table["site"]}/library";
			$tableFolder = "{$moduleFolder}/{$table["folder"]}";
			$generatedFolder = "{$moduleFolder}/Generated/{$table["folder"]}";
			$stubFolder = "{$generatedFolder}/Stubs";
			self::makeFolder($tableFolder);
			self::makeFolder($stubFolder);
			$text = implode(NL, array(
			"<?php",
			"/**",
			" * DO NOT EDIT -- This is an auto-generated class from the Helix Class Generator",
			" * ",
			" * This class represents the {$table["name"]} table in the Helix database schema.",
			" * Use this class to select, insert, update and delete data in the {$table["name"]}",
			" * table, as well as access related data in other tables.",
			" * ",
			" * If you need to extend the functionality of this class, code should be placed in a",
			" * class called {$table["class"]}Extension, and should extend the {$table["class"]}Table",
			" * class.  The custom code file should be in the helix/modules/{$table["module"]} folder",
			" * and should be called {$table["class"]}Extension.php",
			" * ",
			" * " . self::inheritanceBreadCrumbs($table),
			" */",
			"class {$table["class"]}Table extends " . ($table["is_child"] ? self::$tables[$table["parent"]]["class"] : "Object") . " {",
			"	public \$logSequence;",
			"	public \$fdate;",
			"	public \$tdate;",
			(!$table["is_child"] ? implode(NL, array(
			"",
			"	protected \$_cache = array();",
			"	protected \$_initial = array();",
			"	protected \$_snapshot;",
			""
			)) :
			""
			),
				self::defineProperties($table),
			"",
			"	public function __construct(" . self::listConstructorArgs($table, true) . ") {",
			"		\$db = Database::getInstance();",
					self::setPropertyDefaults($table),
			"",
					self::setParentJoinCondition($table),
			"",
			"		if (isset(\$condition)) {",
						self::setSelectStatement($table),
			"",
			"			\$db->query(\$query);",
			"",
			"			if (\$db->getRecord() && \$db->getNumRows()===1) {",
							self::setProperties($table),
			"			} else {",
			"				\$this->id = null;",
			"			}",
			"		}",
			"",
					self::setInitialValueArray($table),
			"	}",
			"",
			"	public static function snapshot(\$date, " . self::listConstructorArgs($table, true, true) . ") {",
			"		\$object = new {$table["class"]}();",
			"		\$object->_snapshot = \$date;",
			"		\$db = Database::getInstance();",
			"		\$db->changeDatabase(\$db->getDatabase() . \"_log\");",
			"",
					self::setParentJoinCondition($table),
			"",
			"		if (isset(\$condition)) {",
						self::setSnapshotCondition($table),
						self::setSelectStatement($table, null, true),
			"",
			"			\$db->query(\$query);",
			"",
			"			if (\$db->getRecord() && \$db->getNumRows()===1) {",
			"				\$object->logSequence = \$db->record[\"log_sequence\"];",
			"				\$object->fdate = new Date(\$db->record[\"fdate\"]);",
			"				\$object->tdate = new Date(\$db->record[\"tdate\"]);",
							self::setProperties($table, "object"),
			"			} else {",
			"				\$object->id = null;",
			"			}",
			"		}",
			"		",
			"		return \$object;",
			"	}",
			"",
			"	public function __call(\$method, \$arguments) {",
			"		if (preg_match('/^set(.*)$/', \$method, \$matches)) {",
			"			\$property = lcfirst(\$matches[1]);",
			"			\$this->{\$property} = \$arguments[0];",
			"		}",
			"		return \$this;",
			"	}",
			"",
			"	public function __get(\$property) {",
			"		if (method_exists(\$this, \"get{\$property}\")) {",
			"			return \$this->{\"get{\$property}\"}();",
			"		} else if (strstr(\$property, \"_\")) {",
			"			list(\$type, \$method) = explode(\"_\", \$property, 2);",
			"			return method_exists(\$this, \"get{\$method}\") ? \$this->{\"get{\$method}\"}(\$type) : null;",
			"		} else {",
			"			return null;",
			"		}",
			"	}",
			"",
			"	public function __set(\$property, \$value) {",
			"		if (isset(\$this->_snapshot)) {return false;}",
			"		if (method_exists(\$this, \"set{\$property}\")) {",
			"			\$this->{\"set{\$property}\"}(\$value);",
			"		} else if (strstr(\$property, \"_\")) {",
			"			list(\$type, \$method) = explode(\"_\", \$property, 2);",
			"			if (method_exists(\$this, \"set{\$method}\")) {",
			"				\$this->{\"set{\$method}\"}(\$value, \$type);",
			"			}",
			"		}",
			"		return \$this;",
			"	}",
			"",
			"	public function isDirty() {",
			"		\$isDirty = false;",
			"",
					self::checkIsDirty($table),
			"",
			"		return \$isDirty;",
			"	}",
			"",
			"	public function save() {",
			"		if (isset(\$this->_snapshot)) {return false;}",
			"		\$status = \$this->id>0 ? \$this->update() : \$this->insert();",
			"",
			"		foreach (\$this->_cache as \$class=>\$list) {",
			"			foreach (\$list as \$type=>\$object) {",
			"				\$object->save();",
			"				\$this->{\"add\" . \$object->getClass()}(\$object, \$type);",
			"			}",
			"			unset(\$this->_cache[\$class]);",
			"		}",
			"",
			"		return \$status;",
			"	}",
			"",
			"	public function insert(" . ($table["is_child"] ? "\$insertParent=true" : "") . ") {",
			"		if (isset(\$this->_snapshot)) {return false;}",
			"		global \$session, \$config;",
			"",
			"		\$db = Database::getInstance();",
					self::setInsertStatement($table),
			"",
			"		if (\$config[\"enable_database_log\"]) {",
			"			\$this->log();",
			"		}",
			"",
			"		return \$status;",
			"	}",
			"",
			"	public function update() {",
			"		if (isset(\$this->_snapshot)) {return false;}",
			"		global \$session, \$config;",
			"",
			($table["is_child"] ? implode(NL, array(
			"		parent::update();",
			"		if (\$this->isDirty()) {"
			)) :
			"		if (\$this->isDirty()) {"
			),
			"			\$db = Database::getInstance();",
						self::setUpdateStatement($table),
			"			\$status = \$db->query(\$query);",
			"",
			"			if (\$config[\"enable_database_log\"]) {",
			"				\$this->log();",
			"			}",
			"",
			"			return \$status;",
			"		} else {",
			"			return false;",
			"		}",
			"	}",
			"",
			"	private function log() {",
			"		if (isset(\$this->_snapshot)) {return false;}",
			"		\$db = Database::getInstance();",
			"		\$database = \$db->getDatabase();",
			"		\$log = \"{\$database}_log\";",
			"		\$db->changeDatabase(\$log);",
					self::setLogStatements($table),
			"		\$db->changeDatabase(\$database);",
			"		return \$status;",
			"	}",
			($table["is_child"] && !$table["is_self_linked"]? implode(NL, array(
			"",
			"	public function getParent() {",
			"		\$this->get" . self::$tables[$table["parent"]]["class"] . "();",
			"	}",
			"",
			"	public function addSibling(\$object) {",
			"		if (isset(\$this->_snapshot)) {return false;}",
			"		if (method_exists(\$object, \"getParent\") && is_null(\$object->id) && \$object->getParent()->getClass()===\$this->getParent()->getClass()) {",
			"			\$object->id = \$this->id;",
			"			return \$object->insert(false);",
			"		} else {",
			"			return false;",
			"		}",
			"	}",
			""
			)) : 
			""
			),
			"	public function delete() {",
			"		if (isset(\$this->_snapshot)) {return false;}",
			"		\$this->deleted = true;",
			"		\$status = \$this->update();",
			"		return \$status;",
			"	}",
			"",
			"	public function unDelete() {",
			"		if (isset(\$this->_snapshot)) {return false;}",
			"		\$this->deleted = false;",
			"		\$status = \$this->update();",
			"		return \$status;",
			"	}",
			"",
			"	public function purge() {",
			"		if (isset(\$this->_snapshot)) {return false;}",
			"		\$db = Database::getInstance();",
			"		\$query = \"DELETE FROM {$tablename} WHERE {$table["id_column"]}={\$db->queryValue(\$this->id)}\";",
			"		\$status = \$db->query(\$query);",
			"		return \$status;",
			"	}",
			"",
			"	public static function deleteAll(" . ($table["type"]==="RELATIONSHIP" ? "{$table["params"][0]["param"]}=null, {$table["params"][1]["param"]}=null, \$type=null" : "\$where=null") . ") {",
			"		\$db = Database::getInstance();",
			($table["type"]==="RELATIONSHIP" ? implode(NL, array(
			"		\$conditions = array();",
			"		if (isset({$table["params"][0]["param"]})) {",
			"			\$conditions[] = \"{\$db->le}{$table["params"][0]["column"]}{\$db->re}={\$db->queryValue({$table["params"][0]["param"]})}\";",
			"		}",
			"		if (isset({$table["params"][1]["param"]})) {",
			"			\$conditions[] = \"{\$db->le}{$table["params"][1]["column"]}{\$db->re}={\$db->queryValue({$table["params"][1]["param"]})}\";",
			"		}",
			"		if (isset(\$type)) {",
			"			\$conditions[] = \"{\$db->le}{$table["name"]}_type_id{\$db->re}=\" . \$db->queryValue(self::typeId(\$type));",
			"		}",
			"		\$condition = count(\$conditions)===0 ? \"\" : \" WHERE \" . implode(\" AND \", \$conditions);"
			)) : 
			"		\$condition = isset(\$where) ? \"WHERE {\$where}\" : \"\";"
			),
			"		\$query = \"UPDATE {$tablename} SET deleted=1 {\$condition}\";",
			"		\$status = \$db->query(\$query);",
			"		return \$status;",
			"	}",
			"",
			"	public static function select(\$columns, \$order=null, \$where=null, \$limit=null, \$offset=0) {",
			"		\$db = Database::getInstance();",
			"",
			"		\$records = array();",
			"		\$columns = is_array(\$columns) ? \$columns : explode(\",\", \$columns);",
			"",
					self::setGetStatement($table),
			"",
			"		\$db->query(\$query);",
			"",
			"		if (count(\$columns)>1) {",
			"			while (\$db->getRecord()) {",
			"				\$records[] = \$db->record;",
			"			}",
			"		} else {",
			"			while (\$db->getRecord(false)) {",
			"				\$records[] = \$db->record[0];",
			"			}",
			"		}",
			"",
			"		return new Collection(\$records);",
			"	}",
			"",
			"	public static function ids(\$order=null, \$where=null, \$limit=null, \$offset=0) {",
			"		return {$table["class"]}::select(\"{$table["id_column"]}\", \$order, \$where, \$limit, \$offset);",
			"	}",
			"",
			"	public static function objects(\$order=null, \$where=null, \$limit=null, \$offset=0) {",
			"		\$db = Database::getInstance();",
			"		\$objects = array();",
			"		foreach ({$table["class"]}::select(\"" . self::listConstructorQueryColumns($table) . "\", \$order, \$where, \$limit, \$offset) as \$record) {",
			"			\$object = new {$table["class"]}();",
						self::setProperties($table, "object", "record"),
			"			\$objects[] = \$object;",
			"		}",
			"		return new Collection(\$objects);",
			"	}",
			"",
			"	public static function search(\$query=null, \$order=null, \$where=null, \$limit=null, \$offset=0) {",
			"		\$keywords = array();",
			"		\$clauses = array();",
			"",
			"		preg_match_all('/\"([^\"]+)\"/i', \$query, \$matches, PREG_SET_ORDER);",
			"		foreach (\$matches as \$match) {",
			"			\$keywords[] = \$match[1];",
			"		}",
			"",
			"		\$query = preg_replace('/\"[^\"]+\"/i', '', \$query);",
			"		foreach (preg_split('/ +/i',\$query) as \$keyword) {",
			"			\$keywords[] = \$keyword;",
			"		}",
			"",
			"		foreach (\$keywords as \$keyword) {",
			"			\$clauses[] = \"" . (is_null(self::searchClause($table)) ? "id LIKE '%{\$keyword}%'" : self::searchClause($table)) . "\";",
			"		}",
			"",
			"		\$search = implode(\" AND \", \$clauses);",
			"		\$where = isset(\$where) ? \"{\$where} AND ({\$search})\" : \"({\$search})\";",
			"		return {$table["class"]}::objects(\$order, \$where, \$limit, \$offset);",
			"	}",
			"",
			"	public function __toString() {",
			"		return " . ($table["is_column_extension"] ? "\$this->value" : "\"{$table["class"]} Object [\" . alt(\$this->id, \"null\") . \"]\"") . ";",
			"	}",
			"",
			"	public function string() {",
			"		return \$this->__toString();",
			"	}",
				self::typeMethods($table),
				self::referencedByObjects($table),
				self::referencedObjects($table),
				self::linkedObjects($table),
				($table["is_self_linked"] ? self::selfLinkedObjects($table) : ""),
			"}",
			"?>"
			));
			file_put_contents("{$generatedFolder}/{$table["class"]}Table.php", $text);
			self::$classes["{$table["class"]}Table"] = "{$generatedFolder}/{$table["class"]}Table.php";
			self::$linesOfCode += count(explode(NL, $text));
			
			$text = implode(NL, array(
			"<?php",
			"/**",
			" * DO NOT EDIT -- This is an auto-generated class from the Helix Class Generator",
			" * ",
			" * This class should never contain any code.",
			" * ",
			" * " . self::inheritanceBreadCrumbs($table),
			" */",
			"class {$table["class"]} extends {$table["class"]}Extension {}",
			"?>"
			));
			file_put_contents("{$stubFolder}/{$table["class"]}.php", $text);
			self::$classes[$table["class"]] = "{$stubFolder}/{$table["class"]}.php";
			self::$linesOfCode += count(explode(NL, $text));
			
			$text = implode(NL, array(
			"<?php",
			"/**",
			" * DO NOT EDIT -- This is an auto-generated class from the Helix Class Generator",
			" * ",
			" * This class should never contain any code.",
			" * ",
			" * " . self::inheritanceBreadCrumbs($table),
			" */",
			"class {$table["class"]}Relationships extends {$table["class"]}Table {}",
			"?>"
			));
			file_put_contents("{$stubFolder}/{$table["class"]}Relationships.php", $text);
			self::$classes["{$table["class"]}Relationships"] = "{$stubFolder}/{$table["class"]}Relationships.php";
			self::$linesOfCode += count(explode(NL, $text));

			if (file_exists("{$tableFolder}/{$table["class"]}Extension.php")) {
				$text = file_get_contents("{$tableFolder}/{$table["class"]}Extension.php");
			} else {
				$text = implode(NL, array(
				"<?php",
				"/**",
				" * This is an extension of the {$table["class"]}Relationships class in the Helix Class Library",
				" * ",
				" * Add methods and/or properties to this class to extend the functionality of the",
				" * {$table["class"]} class family.  Changes to this class will affect all sites that use",
				" * this installation of the Helix Class Library.",
				" * ",
				" * If you need to customize this class for a single site, custom code should be placed",
				" * in a class called {$table["class"]}, and should extend the {$table["class"]}Extension class.",
				" * The custom code file should be in the site folder called: library/{$table["class"]}.php",
				" * ",
				" * " . self::inheritanceBreadCrumbs($table),
				" */",
				"class {$table["class"]}Extension extends {$table["class"]}Relationships {",
				"",
				"}",
				"?>"
				));
			}
			file_put_contents("{$tableFolder}/{$table["class"]}Extension.php", $text);
			self::$classes["{$table["class"]}Extension"] = "{$tableFolder}/{$table["class"]}Extension.php";
			self::$linesOfCode += count(explode(NL, $text));
		}
	}
	
	private static function linkedObjects($table) {
		$classCode = null;
		$siteClassCode = null;
		
		if (isset($table["linked"])) {
			ksort($table["linked"]);
			foreach ($table["linked"] as $linktable=>$linked) {
				$lines = array();
				if ($table["name"]===$linktable) {continue;}
				$link = self::$tables[$linktable];
				$rel = self::$tables[$linked["relationship"]["table"]];
				$param = "\$" . lcfirst($link["class"]);
				$lines[] = "";
				$lines[] = TAB . "public function set{$link["class"]}({$param}=null, \$type=\"default\") {";
				$lines[] = TAB . TAB . "if (isset(\$this->_snapshot)) {return false;}";
				$lines[] = TAB . TAB . "\$this->remove{$link["class"]}List(\$type);";
				if ($link["is_column_extension"]) {
					$lines[] = TAB . TAB . "{$param} = is_object({$param}) || is_int({$param}) ? {$param} : \$this->get{$link["class"]}(\$type, true)->setValue({$param});";
				}
				$lines[] = TAB . TAB . "\$this->add{$link["class"]}({$param}, \$type);";
				$lines[] = TAB . TAB . "return \$this;";
				$lines[] = TAB . "}";
				
				$lines[] = TAB . "public function remove{$link["class"]}({$param}, \$type=\"default\", \$deleteObject=true) {";
				$lines[] = TAB . TAB . "if (isset(\$this->_snapshot)) {return false;}";
				$lines[] = TAB . TAB . "\$list = is_array({$param}) ? {$param} : array({$param});";
				$lines[] = TAB . TAB . "foreach (\$list as \$item) {";
				$lines[] = TAB . TAB . "	\$id = is_object(\$item) ? \$item->id : \$item;";
				$lines[] = TAB . TAB . "	\$relationship = \$this->get{$rel["class"]}(\$id, \$type);";
				$lines[] = TAB . TAB . "	if (\$deleteObject) {";
				$lines[] = TAB . TAB . "		\$relationship->get{$link["class"]}()->delete();";
				$lines[] = TAB . TAB . "	}";
				$lines[] = TAB . TAB . "	\$relationship->delete();";
				$lines[] = TAB . TAB . "}";
				$lines[] = TAB . TAB . "return \$this;";
				$lines[] = TAB . "}";
				
				$lines[] = TAB . "public function remove{$link["class"]}List(\$type=null) {";
				$lines[] = TAB . TAB . "if (isset(\$this->_snapshot)) {return false;}";
				$lines[] = TAB . TAB . "if (\$this->id>0) {";
				$lines[] = TAB . TAB . "	return {$rel["class"]}::deleteAll(" . ($rel["params"][0]["table"]===$table["name"] ? "\$this->id" : "null") . ", " . ($rel["params"][1]["table"]===$table["name"] ? "\$this->id" : "null") . ", \$type);";
				$lines[] = TAB . TAB . "}";
				$lines[] = TAB . "}";
				
				$lines[] = TAB . "public function add{$link["class"]}({$param}=null, \$type=\"default\") {";
				$lines[] = TAB . TAB . "if (isset(\$this->_snapshot)) {return false;}";
				$lines[] = TAB . TAB . "if (isset({$param})) {";
				$lines[] = TAB . TAB . "	if (!\$this->id) {";
				$lines[] = TAB . TAB . "		\$this->save();";
				$lines[] = TAB . TAB . "	}";
				$lines[] = TAB . TAB . "	\$list = is_array({$param}) ? {$param} : array({$param});";
				$lines[] = TAB . TAB . "	\$order = 0;";
				$lines[] = TAB . TAB . "	foreach (\$list as \$item) {";
				$lines[] = TAB . TAB . "		if (is_object(\$item) && !\$item->id) {";
				$lines[] = TAB . TAB . "			\$item->save();";
				$lines[] = TAB . TAB . "		}";
				$lines[] = TAB . TAB . "		\$id = is_object(\$item) ? \$item->id : \$item;";
				$lines[] = TAB . TAB . "		\$relationship = \$this->get{$rel["class"]}(\$id, \$type);";
				if ($rel["is_ordered"]) {
					$lines[] = TAB . TAB . "		\$relationship->order = ++\$order;";
				}
				$lines[] = TAB . TAB . "		\$relationship->deleted = false;";
				$lines[] = TAB . TAB . "		\$relationship->save();";
				$lines[] = TAB . TAB . "	}";
				$lines[] = TAB . TAB . "}";
				$lines[] = TAB . TAB . "return \$this;";
				$lines[] = TAB . "}";
				
				$lines[] = TAB . "public function get{$link["class"]}(\$type=\"default\"" . ($link["is_column_extension"] ? ", \$getAsObject=false" : "") . ") {";
				$lines[] = TAB . TAB . "if (isset(\$this->_cache[\"{$link["class"]}\"]) && isset(\$this->_cache[\"{$link["class"]}\"][\$type])) {";
				$lines[] = TAB . TAB . "	{$param} = \$this->_cache[\"{$link["class"]}\"][\$type];";
				$lines[] = TAB . TAB . "} else {";
				$lines[] = TAB . TAB . "	{$param} = new {$link["class"]}(\$this->get{$link["class"]}Id(\$type));";
				$lines[] = TAB . TAB . "}";
				$lines[] = TAB . TAB . "\$this->_cache[\"{$link["class"]}\"][\$type] = {$param};";
				if ($link["is_column_extension"]) {
					$lines[] = TAB . TAB . "return \$getAsObject ? \$this->_cache[\"{$link["class"]}\"][\$type] : \$this->_cache[\"{$link["class"]}\"][\$type]->value;";
				} else {
					$lines[] = TAB . TAB . "return \$this->_cache[\"{$link["class"]}\"][\$type];";
				}
				$lines[] = TAB . "}";
				
				$lines[] = TAB . "public function get{$link["class"]}List(\$type=null, \$order=null, \$where=null, \$limit=null, \$offset=0, \$primary=false) {";
				$lines[] = TAB . TAB . "\$db = Database::getInstance();";
				$lines[] = TAB . TAB . "\$ids = \$this->get{$link["class"]}Ids(\$type, \$order, \$where, \$limit, \$offset, \$primary);";
				$lines[] = TAB . TAB . "\$list = \$ids->count()===0 ? new Collection() : {$link["class"]}::objects(\$order, \"{\$db->le}{$link["name"]}{\$db->le}.{\$db->re}{$link["id_column"]}{\$db->re} IN (\" . \$ids->join(\",\") . \")\");";
				$lines[] = TAB . TAB . "return \$list;";
				$lines[] = TAB . "}";
				
				$lines[] = TAB . "public function get{$link["class"]}Id(\$type=\"default\") {";
				$lines[] = TAB . TAB . "return \$this->get{$link["class"]}Ids(\$type)->get(0);";
				$lines[] = TAB . "}";
				
				$lines[] = TAB . "public function get{$link["class"]}Ids(\$type=null, \$order=null, \$where=null, \$limit=null, \$offset=0, \$primary=false) {";
				$lines[] = TAB . TAB . "\$db = Database::getInstance();";
				$lines[] = TAB . TAB . "\$ids = array();";
				$lines[] = TAB . TAB . "";
				$lines[] = TAB . TAB . "if (isset(\$this->_snapshot)) {";
				$lines[] = TAB . TAB . TAB . "\$date = \$this->_snapshot;";
				$lines[] = TAB . TAB . TAB . "\$condition = \" {\$db->le}{$linktable}{\$db->re}.{\$db->le}tdate{\$db->re} IS NOT NULL AND {\$db->le}{$linktable}{\$db->re}.{\$db->le}fdate{\$db->re}<={\$db->queryValue(\$date)} AND {\$db->queryValue(\$date)}<={\$db->le}{$linktable}{\$db->re}.{\$db->le}tdate{\$db->re} \";";
				$lines[] = TAB . TAB . TAB . "\$where = isset(\$where) ? \"{\$where} AND ({\$condition})\" : \$condition;";
				$lines[] = TAB . TAB . "}";
				$lines[] = TAB . TAB . "";
				$lines[] = TAB . TAB . "\$query = implode(NL, array(";
				$lines[] = TAB . TAB . TAB . "\"SELECT {\$db->le}{$linktable}{\$db->re}.{\$db->le}{$link["id_column"]}{\$db->re} \",";
				$lines[] = TAB . TAB . TAB . "\"FROM {\$db->le}{$linktable}{\$db->re} \",";
				$lines[] = TAB . TAB . TAB . "\"INNER JOIN {\$db->le}{$linked["relationship"]["table"]}{\$db->re} ON {\$db->le}{$linked["relationship"]["table"]}{\$db->re}.{\$db->le}{$linked["relationship"]["linked"]}{\$db->re}={\$db->le}{$linktable}{\$db->re}.{\$db->le}{$link["id_column"]}{\$db->re} \",";
				$lines[] = TAB . TAB . TAB . "\"	AND {\$db->le}{$linked["relationship"]["table"]}{\$db->re}.{\$db->le}deleted{\$db->re}=0 \",";
				$lines[] = TAB . TAB . TAB . "\"	AND {\$db->le}{$linktable}{\$db->re}.{\$db->le}deleted{\$db->re}=0 \",";
				$lines[] = TAB . TAB . TAB . "\"	AND {\$db->le}{$linked["relationship"]["table"]}{\$db->re}.{\$db->le}{$linked["relationship"]["local"]}{\$db->re}={\$db->queryValue(\$this->id)} \",";
				$lines[] = TAB . TAB . TAB . "(isset(\$type) ? \"	AND {\$db->le}{$linked["relationship"]["table"]}{\$db->re}.{\$db->le}{$linked["relationship"]["table"]}_type_id{\$db->re}=\" . \$db->queryValue({$rel["class"]}::typeId(\$type)) . \" \" : \"\"),";
				$lines[] = TAB . TAB . TAB . "(\$primary ? \"	AND {\$db->le}{$linked["relationship"]["table"]}{\$db->re}.{\$db->le}primary{\$db->re}=1 \" : \"\"),";
				$lines[] = TAB . TAB . TAB . "(isset(\$where) ? \" WHERE {\$where} \" : \"\"),";
				$lines[] = TAB . TAB . TAB . "(isset(\$order) ? \" ORDER BY " . ($link["is_ordered"] ? "\" . alt(\"{\$order}\", \"{\$db->le}{$linktable}{\$db->re}.{\$db->le}order{\$db->re}\")" : "{\$order}\"") . " : \"\"),";
				$lines[] = TAB . TAB . TAB . "(isset(\$limit) ? \" LIMIT {\$offset},{\$limit} \" : \"\")";
				$lines[] = TAB . TAB . "));";
				$lines[] = TAB . TAB . "";
				$lines[] = TAB . TAB . "\$db->query(\$query);";
				$lines[] = TAB . TAB . "";
				$lines[] = TAB . TAB . "while (\$db->getRecord()) {";
				$lines[] = TAB . TAB . "	\$ids[] = \$db->record[\"{$link["id_column"]}\"];";
				$lines[] = TAB . TAB . "}";
				$lines[] = TAB . TAB . "";
				$lines[] = TAB . TAB . "return new Collection(\$ids);";
				$lines[] = TAB . "}";
				$code = implode(NL, $lines);
				if (isset($table["module"]) && isset(self::$tables[$linktable]["site"])) {
					$siteClassCode .= (isset($siteClassCode) ? NL : "") . $code;
				} else {
					$classCode .= (isset($classCode) ? NL : "") . $code;
				}
			}
			
			// THIS IS THE SECOND HALF OF THE SITE RELATIONSHIPS CLASS
			// THE FIRST HALF IS GENERATED IN THE referencedByObjects() METHOD
			$target = dirname(realpath(".")) . "/" . self::$site . "/library/Generated/{$table["class"]}Relationships.php";
			if (isset($siteClassCode) || file_exists($target)) {
				if (file_exists($target)) {
					$existingText = file_get_contents($target);
				} else {
					$existingText = implode(NL, array(
					"<?php",
					"/**",
					" * DO NOT EDIT -- This is an auto-generated class from the Helix Class Generator",
					" * ",
					" * " . self::inheritanceBreadCrumbs($table),
					" */",
					"class {$table["class"]}Relationships extends {$table["class"]}Table {"
					));
				}
				$text = implode(NL, array(
				$existingText,
				$siteClassCode,
				"",
				"}",
				"?>"
				));
				file_put_contents($target, $text);
				self::$linesOfCode += count(explode(NL, $text));
			}
		}
		
		return $classCode;
	}
	
	private static function selfLinkedObjects($table) {
		$lines = array();
		
		$lines[] = "";
		$lines[] = TAB . "public function getParent(\$type=\"default\") {";
		$lines[] = TAB . TAB . "\$db = Database::getInstance();";
		$lines[] = TAB . TAB . "\$relationships = {$table["class"]}{$table["class"]}::objects(null, \"{\$db->le}child_{$table["name"]}_id{\$db->re}='{\$this->id}' AND {\$db->le}{$table["name"]}_{$table["name"]}_type_id{\$db->re}='\" . {$table["class"]}{$table["class"]}::typeId(\$type) . \"'\");";
		$lines[] = TAB . TAB . "return (\$relationships->count()===1) ? \$relationships->get(0)->getParent() : new {$table["class"]}();";
		$lines[] = TAB . "}";
		
		$lines[] = "";
		$lines[] = TAB . "public function getChildIds() {";
		$lines[] = TAB . TAB . "\$db = Database::getInstance();";
		$lines[] = TAB . TAB . "return {$table["class"]}{$table["class"]}::select(\"{$table["name"]}_{$table["name"]}.child_{$table["name"]}_id\", " . (self::$tables["{$table["name"]}_{$table["name"]}"]["is_ordered"] ? "\"{\$db->le}order{\$db->re} ASC\"" : "null") . ", \"{$table["name"]}_{$table["name"]}.parent_{$table["name"]}_id={\$this->id}\");";
		$lines[] = TAB . "}";
		
		$lines[] = "";
		$lines[] = TAB . "public function getChildren() {";
		$lines[] = TAB . TAB . "return \$this->hasChildren() ? {$table["class"]}::objects(null, \"{$table["name"]}.{$table["id_column"]} IN (\" . \$this->getChildIds()->join(\",\") . \")\") : new Collection();";
		$lines[] = TAB . "}";
		
		$lines[] = "";
		$lines[] = TAB . "public function getChildCount() {";
		$lines[] = TAB . TAB . "return \$this->getChildIds()->count();";
		$lines[] = TAB . "}";
		
		$lines[] = "";
		$lines[] = TAB . "public function hasChildren() {";
		$lines[] = TAB . TAB . "return \$this->getChildCount()>0;";
		$lines[] = TAB . "}";
		
		$lines[] = "";
		$lines[] = TAB . "public function isChild() {";
		$lines[] = TAB . TAB . "return \$this->getParent()->id>0;";
		$lines[] = TAB . "}";
		
		$lines[] = "";
		$lines[] = TAB . "public function isRoot() {";
		$lines[] = TAB . TAB . "return \$this->id>0 && !\$this->isChild();";
		$lines[] = TAB . "}";
		
		$lines[] = "";
		$lines[] = TAB . "public function getSiblingIds() {";
		$lines[] = TAB . TAB . "\$db = Database::getInstance();";
		$lines[] = TAB . TAB . "return {$table["class"]}{$table["class"]}::select(\"{$table["name"]}_{$table["name"]}.child_{$table["name"]}_id\", " . (self::$tables["{$table["name"]}_{$table["name"]}"]["is_ordered"] ? "\"{\$db->le}order{\$db->re} ASC\"" : "null") . ", \"{$table["name"]}_{$table["name"]}.child_{$table["name"]}_id<>'{\$this->id}' AND {$table["name"]}_{$table["name"]}.parent_{$table["name"]}_id=\" . \$db->queryValue(\$this->getParent()->id));";
		$lines[] = TAB . "}";
		
		$lines[] = "";
		$lines[] = TAB . "public function getSiblings() {";
		$lines[] = TAB . TAB . "return \$this->hasSiblings() ? {$table["class"]}::objects(null, \"{$table["name"]}.{$table["id_column"]} IN (\" . \$this->getSiblingIds()->join(\",\") . \")\") : new Collection();";
		$lines[] = TAB . "}";
		
		$lines[] = "";
		$lines[] = TAB . "public function getSiblingCount() {";
		$lines[] = TAB . TAB . "return \$this->getSiblingIds()->count();";
		$lines[] = TAB . "}";
		
		$lines[] = "";
		$lines[] = TAB . "public function hasSiblings() {";
		$lines[] = TAB . TAB . "return \$this->getSiblingCount()>0;";
		$lines[] = TAB . "}";
		
		$lines[] = "";
		$lines[] = TAB . "public function getDescendants() {";
		$lines[] = TAB . TAB . "\$descendants = new Collection();";
		$lines[] = TAB . TAB . "foreach (\$this->getChildren() as \$child) {";
		$lines[] = TAB . TAB . "	\$descendants->add(\$child);";
		$lines[] = TAB . TAB . "	if (\$child->hasChildren()) {";
		$lines[] = TAB . TAB . "		\$descendants->add(\$child->getDescendants());";
		$lines[] = TAB . TAB . "	}";
		$lines[] = TAB . TAB . "}";
		$lines[] = TAB . TAB . "return \$descendants;";
		$lines[] = TAB . "}";
		
		$lines[] = "";
		$lines[] = TAB . "public function getAncestors() {";
		$lines[] = TAB . TAB . "\$ancestors = new Collection();";
		$lines[] = TAB . TAB . "\$child = \$this;";
		$lines[] = TAB . TAB . "while (\$child->isChild()) {";
		$lines[] = TAB . TAB . "	\$parent = \$child->getParent();";
		$lines[] = TAB . TAB . "	\$ancestors->add(\$parent);";
		$lines[] = TAB . TAB . "	\$child = \$parent;";
		$lines[] = TAB . TAB . "}";
		$lines[] = TAB . TAB . "return \$ancestors;";
		$lines[] = TAB . "}";
		
		$lines[] = "";
		$lines[] = TAB . "public function getLevel() {";
		$lines[] = TAB . TAB . "\$level = 0;";
		$lines[] = TAB . TAB . "\$child = \$this;";
		$lines[] = TAB . TAB . "while (\$child->isChild()) {";
		$lines[] = TAB . TAB . "	\$level++;";
		$lines[] = TAB . TAB . "	\$child = \$child->getParent();";
		$lines[] = TAB . TAB . "}";
		$lines[] = TAB . TAB . "return \$level;";
		$lines[] = TAB . "}";
		
		$lines[] = "";
		
		return implode(NL, $lines);
	}
	
	private static function referencedByObjects($table) {
		$classCode = null;
		$siteClassCode = null;
		
		if (isset($table["referenced_by"]) && $table["type"]==="STANDARD") {
			foreach ($table["referenced_by"] as $refbytable=>$referenced_by) {
				$lines = array();
				if (isset($referenced_by["linked_table"])) {
					$refby = self::$tables[$refbytable];
					if ($refby["is_self_referential"]) {continue;}
					$refbyParam = "\$" . lcfirst(self::$tables[$referenced_by["linked_table"]]["class"]) . "Id";
					$columns = array($table["name"]=>"\$this->id", $referenced_by["linked_table"]=>$refbyParam);
					ksort($columns);
					$lines[] = "";
					$lines[] = TAB . "public function get{$refby["class"]}({$refbyParam}, \$type=\"default\") {";
					$lines[] = TAB . TAB . "return new {$refby["class"]}(null, " . implode(", ", $columns) . ", {$refby["class"]}::typeId(\$type));";
					$lines[] = TAB . "}";
					$code = implode(NL, $lines);
					if (isset($table["module"]) && isset(self::$tables[$referenced_by["linked_table"]]["site"])) {
						$siteClassCode .= (isset($siteClassCode) ? NL : "") . $code;
					} else {
						$classCode .= (isset($classCode) ? NL : "") . $code;
					}
				} 
			}
			
			// THIS IS THE FIRST HALF OF THE SITE RELATIONSHIPS CLASS
			// THE SECOND HALF IS GENERATED IN THE linkedObjects() METHOD
			if (isset($siteClassCode)) {
				$text = implode(NL, array(
				"<?php",
				"/**",
				" * DO NOT EDIT -- This is an auto-generated class from the Helix Class Generator",
				" * ",
				" * " . self::inheritanceBreadCrumbs($table),
				" */",
				"class {$table["class"]}Relationships extends {$table["class"]}Table {",
				$siteClassCode
				));
				file_put_contents(dirname(realpath(".")) . "/" . self::$site . "/library/Generated/{$table["class"]}Relationships.php", $text);
				self::$linesOfCode += count(explode(NL, $text));
			}
		}
		
		return $classCode;
	}
	
	private static function referencedObjects($table) {
		$lines = array();
		
		if (isset($table["references"])) {
			foreach ($table["references"] as $reftable=>$reference) {
				$methodClass = preg_match('/^child|parent$/', $reftable) ? ucfirst($reftable) : self::$tables[$reftable]["class"];
				$class = preg_match('/^child|parent$/', $reftable) ? ucfirst($table["parts"][0]) : $methodClass;
				if ($table["type"]!=="RELATIONSHIP" && in_array($reference["local"], $table["primary_keys"])) {
					$prop = "id";
					$readOnly = true;
				} else {
					$prop = $table["columns"][$reference["local"]]["property"];
					$readOnly = false;
				}
				$lines[] = "";
				$lines[] = TAB . "public function get{$methodClass}() {";
				$lines[] = TAB . TAB . "return new {$class}(\$this->{$prop});";
				$lines[] = TAB . "}";
				if ($readOnly) {continue;}
				$param = "\$" . lcfirst($methodClass);
				$lines[] = "";
				$lines[] = TAB . "public function set{$methodClass}({$class} {$param}) {";
				$lines[] = TAB . TAB . "if ({$param}->id>0) {";
				$lines[] = TAB . TAB . TAB . "\$this->{$prop} = {$param}->id;";
				$lines[] = TAB . TAB . "}";
				$lines[] = TAB . "}"; 
			}
		}
		
		return implode(NL, $lines);
	}
	
	private static function typeMethods($table) {
		$lines = array();
		
		if ($table["has_type"]) {
			$lines[] = "";
			$lines[] = TAB . "public static function typeId(\$typeName=null) {";
			$lines[] = TAB . TAB . "\$type = new {$table["class"]}Type(null, \$typeName);";
			$lines[] = TAB . TAB . "return alt(\$type->id, 0);";
			$lines[] = TAB . "}";
			$lines[] = "";
			$lines[] = TAB . "public function getType() {";
			$lines[] = TAB . TAB . "\$type = new {$table["class"]}Type(\$this->{$table["name"]}_type_id);";
			$lines[] = TAB . TAB . "return \$type->name;";
			$lines[] = TAB . "}";
			$lines[] = "";
			$lines[] = TAB . "public function setType(\$typeName=null) {";
			$lines[] = TAB . TAB . "if (isset(\$this->_snapshot)) {return false;}";
			$lines[] = TAB . TAB . "\$type = new {$table["class"]}Type(null, \$typeName);";
			$lines[] = TAB . TAB . "\$this->{$table["name"]}_type_id = \$type->id;";
			$lines[] = TAB . TAB . "return \$this->{$table["name"]}_type_id;";
			$lines[] = TAB . "}";
		}
		
		return implode(NL, $lines);
	}
	
	private static function searchClause($table) {
		$clauses = array();
		foreach ($table["collapsed_columns"] as $colname=>$column) {
			$type = is_array($column["type"]) ? $column["type"][0] : $column["type"];
			if (preg_match('/^char|varchar|tinytext|text|mediumtext|longtext|enum|set$/i', $type)) {
				$clauses[] = "{$column["table"]}.{$colname} LIKE '%{\$keyword}%'";
			}
		}
		return count($clauses)>0 ? implode(" OR ", $clauses) : null;
	}
	
	private static function inheritanceBreadCrumbs($table) {
		$chain = array($table["class"]);
		
		$child = $table;
		while ($child["is_child"]) {
			$parent = self::$tables[$child["parent"]];
			array_unshift($chain, $parent["class"]);
			$child = $parent;
		}
		return "Object -> " . implode(" -> ", $chain);
	}
	
	private static function setProperties($table, $objectVar="this", $valueVar="db->record") {
		$lines = array();
		
		foreach ($table["nice_collapsed_columns"] as $colname=>$column) {
			$type = is_array($column["type"]) ? $column["type"][0] : $column["type"];
			if (preg_match('/^date|time|timestamp|datetime|year$/i',$type)) {
				$val = "new Date(\${$valueVar}[\"{$colname}\"])";
			} else {
				$val = "\${$valueVar}[\"{$colname}\"]";
			}
			$lines[] = TAB . TAB . TAB . TAB . "\${$objectVar}->{$column["property"]} = {$val};";
		}
		
		return implode(NL, $lines);
	}
	
	private static function setInitialValueArray($table, $objectVar="this") {
		$lines = array();
		
		foreach ($table["nice_collapsed_columns"] as $colname=>$column) {
			if ($colname==="mdate" || $colname==="cdate" || $colname==="updated_by_id") {continue;}
			$lines[] = TAB . TAB . "\${$objectVar}->_initial[\"{$column["property"]}\"] = \${$objectVar}->{$column["property"]};";
		}
		
		return implode(NL, $lines);
	}
	
	private static function checkIsDirty($table) {
		$lines = array();
		
		foreach ($table["nice_collapsed_columns"] as $colname=>$column) {
			if ($colname==="mdate" || $colname==="cdate" || $colname==="updated_by_id") {continue;}
			$type = is_array($column["type"]) ? $column["type"][0] : $column["type"];
			if (preg_match('/^smallint|mediumint|int|integer|bigint$/i',$type)>0) {
				$cast = "int";
			} else if (preg_match('/^bit|tinyint$/i',$type)>0) {
				$cast = "bool";
			} else if (preg_match('/^real|double|float|decimal|numeric$/i',$type)>0) {
				$cast = "float";
			} else if (preg_match('/^char|varchar|binary|varbinary|tinyblob|blob|mediumblob|longblob|tinytext|text|mediumtext|longtext|enum|set$/i',$type)>0) {
				$cast = "string";
			} else {
				$cast = "string";
			}
			$comparison = preg_match('/^date|time|timestamp|datetime|year$/i',$type) ? "!=" : "!==";
			$lines[] = TAB . TAB . "\$isDirty = \$isDirty || (({$cast})\$this->{$column["property"]} {$comparison} ({$cast})\$this->_initial[\"{$column["property"]}\"]);";
		}
		
		return implode(NL, $lines);
	}
	
	private static function setInsertStatement($table) {
		$lines = array();
		
		if ($table["is_child"]) {
			$lines[] = TAB . TAB . "if (\$insertParent) {";
			$lines[] = TAB . TAB . "	parent::insert();";
			$lines[] = TAB . TAB . "}";
		} else {
			unset ($table["nice_columns"]["id"]);
			unset ($table["insert_columns"]["id"]);
		}
		
		$lines[] = TAB . TAB . "\$query = implode(NL, array(";
		$lines[] = TAB . TAB . TAB . "\"INSERT INTO {\$db->le}{$table["name"]}{\$db->re} (\",";
		$lines[] = TAB . TAB . TAB . "\"	{\$db->le}" . implode("{\$db->re}, {\$db->le}",array_keys($table["insert_columns"])) . "{\$db->re}\",";
		$lines[] = TAB . TAB . TAB . "\") VALUES (\",";
		foreach ($table["nice_columns"] as $colname=>$column) {
			$type = is_array($column["type"]) ? $column["type"][0] : $column["type"];
			if ($colname==="mdate" || $colname==="cdate") {
				$lines[] = TAB . TAB . TAB . TAB . "\$db->queryValue(timestamp()) . \",\",";
			} else if ($colname=="updated_by_id") {
				$lines[] = TAB . TAB . TAB . TAB . "\$db->queryValue((int)\$session->getUserId()) . \",\",";
			} else if (strstr($type, "int") || strstr($type, "bit")) {
				$lines[] = TAB . TAB . TAB . TAB . "\$db->queryValue((int)\$this->{$column["property"]}) . \",\",";
			} else {
				$lines[] = TAB . TAB . TAB . TAB . "\$db->queryValue(\$this->{$column["property"]}) . \",\",";
			}
		}
		$lines[count($lines)-1] = substr($lines[count($lines)-1], 0, -7) . ",";
		$lines[] = TAB . TAB . TAB . "\")\"";
		$lines[] = TAB . TAB . "));";
		$lines[] = TAB . TAB . "\$status = \$db->query(\$query);";
		if (!$table["is_child"]) {
			$lines[] = TAB . TAB . "\$this->id = \$db->getInsertedId();";
		}
		
		return implode(NL, $lines);
	}
	
	private static function setLogStatements($table) {
		$lines = array();
		
		$lines[] = TAB . TAB . "\$query = implode(NL, array(";
		$lines[] = TAB . TAB . TAB . "\"INSERT INTO {\$db->le}{\$log}{\$db->re}.{\$db->le}{$table["name"]}{\$db->re} (\",";
		$lines[] = TAB . TAB . TAB . "\"SELECT\",";
		$lines[] = TAB . TAB . TAB . "\"	NULL,\",";
		$lines[] = TAB . TAB . TAB . "\"	{\$db->le}{\$database}{\$db->re}.{\$db->le}{$table["name"]}{\$db->re}.{\$db->le}mdate{\$db->re},\",";
		$lines[] = TAB . TAB . TAB . "\"	NULL,\",";
		foreach ($table["columns"] as $column) {
			$lines[] = TAB . TAB . TAB . "\"	{\$db->le}{\$database}{\$db->re}.{\$db->le}{$table["name"]}{\$db->re}.{\$db->le}{$column["name"]}{\$db->re},\",";
		}
		$lines[count($lines)-1] = substr($lines[count($lines)-1], 0, -3) . "\",";
		$lines[] = TAB . TAB . TAB . "\"FROM {\$db->le}{\$database}{\$db->re}.{\$db->le}{$table["name"]}{\$db->re}\",";
		$lines[] = TAB . TAB . TAB . "\"WHERE {\$db->le}{\$database}{\$db->re}.{\$db->le}{$table["name"]}{\$db->re}.{\$db->le}{$table["id_column"]}{\$db->re}={\$db->queryValue(\$this->id)}\",";
		$lines[] = TAB . TAB . TAB . "\")\"";
		$lines[] = TAB . TAB . "));";
		$lines[] = TAB . TAB . "\$status = \$db->query(\$query);";
		$lines[] = "";
		$lines[] = TAB . TAB . "\$logSequence = \$db->getInsertedId();";
		$lines[] = "";
		$lines[] = TAB . TAB . "\$query = implode(NL, array(";
		$lines[] = TAB . TAB . TAB . "\"SELECT {\$db->le}log_sequence{\$db->re}\",";
		$lines[] = TAB . TAB . TAB . "\"FROM {\$db->le}{$table["name"]}{\$db->re}\",";
		$lines[] = TAB . TAB . TAB . "\"WHERE {\$db->le}{$table["name"]}{\$db->re}.{\$db->le}{$table["id_column"]}{\$db->re}={\$db->queryValue(\$this->id)}\",";
		$lines[] = TAB . TAB . TAB . "\"	AND {\$db->le}log_sequence{\$db->re}<'{\$logSequence}'\",";
		$lines[] = TAB . TAB . TAB . "\"ORDER BY {\$db->le}log_sequence{\$db->re} DESC\",";
		$lines[] = TAB . TAB . TAB . "\"LIMIT 0,1\"";
		$lines[] = TAB . TAB . "));";
		$lines[] = TAB . TAB . "\$db->query(\$query);";
		$lines[] = "";
		$lines[] = TAB . TAB . "if (\$db->getRecord()) {";
		$lines[] = TAB . TAB . "	\$updateSequence = (int)\$db->record[\"log_sequence\"];";
		$lines[] = TAB . TAB . "	\$query = implode(NL, array(";
		$lines[] = TAB . TAB . TAB . "	\"UPDATE {\$db->le}{\$log}{\$db->re}.{\$db->le}{$table["name"]}{\$db->re}\",";
		$lines[] = TAB . TAB . TAB . "	\"INNER JOIN {\$db->le}{\$database}{\$db->re}.{\$db->le}{$table["name"]}{\$db->re} ON {\$db->le}{\$log}{\$db->re}.{\$db->le}{$table["name"]}{\$db->re}.{\$db->le}{$table["id_column"]}{\$db->re}={\$db->le}{\$database}{\$db->re}.{\$db->le}{$table["name"]}{\$db->re}.{\$db->le}{$table["id_column"]}{\$db->re}\",";
		$lines[] = TAB . TAB . TAB . "	\"	AND {\$db->le}{\$log}{\$db->re}.{\$db->le}{$table["name"]}{\$db->re}.{\$db->le}log_sequence{\$db->re}={\$db->queryValue(\$updateSequence)}\",";
		$lines[] = TAB . TAB . TAB . "	\"SET {\$db->le}{\$log}{\$db->re}.{\$db->le}{$table["name"]}{\$db->re}.{\$db->le}tdate{\$db->re}={\$db->le}{\$database}{\$db->re}.{\$db->le}{$table["name"]}{\$db->re}.{\$db->le}mdate{\$db->re}\"";
		$lines[] = TAB . TAB . "	));";
		$lines[] = TAB . TAB . "	\$db->query(\$query);";
		$lines[] = TAB . TAB . "}";
		
		return implode(NL, $lines);
	}
	
	private static function setUpdateStatement($table) {
		$lines = array();
		
		$idColumn = $table["type"]==="RELATIONSHIP" ? "id" : $table["primary_key"]; 
		
		if (!$table["is_child"]) {
			unset ($table["nice_columns"]["id"]);
		}
		$lines[] = TAB . TAB . TAB . "\$query = implode(NL, array(";
		$lines[] = TAB . TAB . TAB . TAB . "\"UPDATE {\$db->le}{$table["name"]}{\$db->re} SET\",";
		foreach ($table["nice_columns"] as $colname=>$column) {
			if ($colname==="cdate" || ($table["is_child"] && $column["is_primary"])) {continue;}
			$colname = $colname==="id" ? $idColumn : $colname;
			$colprop = $colname===$table["primary_key"] ? "id" : $column["property"];
			$type = is_array($column["type"]) ? $column["type"][0] : $column["type"];
			if ($colname==="mdate" || $colname==="cdate") {
				$lines[] = TAB . TAB . TAB . TAB . TAB . "\"{\$db->le}{$colname}{\$db->re}={\$db->queryValue(timestamp())},\",";
			} else if ($colname==="updated_by_id") {
				$lines[] = TAB . TAB . TAB . TAB . TAB . "\"{\$db->le}{$colname}{\$db->re}={\$db->queryValue((int)\$session->getUserId())},\",";
			} else if (strstr($type, "int")) {
				$lines[] = TAB . TAB . TAB . TAB . TAB . "\"{\$db->le}{$colname}{\$db->re}={\$db->queryValue((int)\$this->{$colprop})},\",";
			} else {
				$lines[] = TAB . TAB . TAB . TAB . TAB . "\"{\$db->le}{$colname}{\$db->re}={\$db->queryValue(\$this->{$colprop})},\",";
			}
		}
		$lines[count($lines)-1] = substr($lines[count($lines)-1], 0, -3) . "\",";
		$lines[] = TAB . TAB . TAB . TAB . "\"WHERE {$idColumn}={\$db->queryValue((int)\$this->id)}\"";
		$lines[] = TAB . TAB . TAB . "));";
		
		return implode(NL, $lines);
	}
	
	private static function setSelectStatement($table, $joinLookupTables=false, $fromLogDatabase=false) {
		$lines = array();
		
		$lines[] = TAB . TAB . TAB . "\$query = implode(NL, array(";
		$lines[] = TAB . TAB . TAB . TAB . "\"SELECT " . self::listConstructorQueryColumns($table, $fromLogDatabase) . "\",";			
		$lines[] = TAB . TAB . TAB . TAB . "\"FROM {\$db->le}{$table["name"]}{\$db->re}\"";
		
		if ($table["is_child"]) {
			$lines[count($lines)-1] .= ",";
			$lines[] = self::joinParentTables($table);
		}
		
		if ($joinLookupTables) {
			$lines[] = self::joinLookupTables($table);
		}
		
		if ($table["type"]==="RELATIONSHIP" || (!$joinLookupTables && !$table["is_child"])) {
			$lines[count($lines)-1] .= ",";
			$lines[] = TAB . TAB . TAB . TAB . "\"WHERE {\$condition}\""; 
		}
		
		$lines[] = TAB . TAB . TAB . "));";
		
		return implode(NL, $lines);
	}
	
	private static function setGetStatement($table) {
		$lines = array();
		
		$lines[] = TAB . TAB . "\$query = implode(NL, array(";
		$lines[] = TAB . TAB . TAB . "\"SELECT \" . implode(\",\", \$columns),";			
		$lines[] = TAB . TAB . TAB . "\"FROM {\$db->le}{$table["name"]}{\$db->le}\",";
		if ($table["is_child"]) {
			$lines[] = self::joinParentTables($table, false) . ",";
		}
		$lines[] = self::joinLookupTables($table, $table["is_child"]);
		$lines[] = TAB . TAB . TAB . "\"WHERE {\$db->le}{$table["name"]}{\$db->re}.{\$db->le}deleted{\$db->re}=0\" . (isset(\$where) ? \" AND ({\$where})\" : \"\"),";
		if ($table["is_ordered"]) {
			$lines[] = TAB . TAB . TAB . "\"ORDER BY \" . alt(\$order, \"{\$db->le}order{\$db->re}\"),";
		} else {
			$lines[] = TAB . TAB . TAB . "(isset(\$order) ? \"ORDER BY {\$order}\" : \"\"),";
		}
		$lines[] = TAB . TAB . TAB . "(isset(\$limit) ? \"LIMIT {\$offset},{\$limit}\" : \"\"),";
		$lines[count($lines)-1] = substr($lines[count($lines)-1], 0, -1);
		$lines[] = TAB . TAB . "));";
		
		return implode(NL, $lines);
	}
	
	private static function listConstructorQueryColumns($table, $fromLogDatabase=false) {
		$columns = array();
		
		if ($fromLogDatabase) {
			$columns[] = "{\$db->le}{$table["name"]}{\$db->re}.{\$db->le}log_sequence{\$db->re}";
			$columns[] = "{\$db->le}{$table["name"]}{\$db->re}.{\$db->le}fdate{\$db->re}";
			$columns[] = "{\$db->le}{$table["name"]}{\$db->re}.{\$db->le}tdate{\$db->re}";
		}
		
		foreach ($table["nice_collapsed_columns"] as $colname=>$column) {
			$columns[] = "{\$db->le}{$column["table"]}{\$db->re}.{\$db->le}{$colname}{\$db->re}";
		}
		
		return implode(", ", $columns);
	}

	private static function joinLookupTables($table, $joinParentLookups=false) {
		$lines = array();
		
		if (isset($table["references"])) {
			foreach ($table["references"] as $reftable=>$reference) {
				if (in_array($reference["local"], $table["primary_keys"])) {continue;}
				$lines[] = TAB . TAB . TAB . "\"LEFT JOIN {\$db->le}{$reftable}{\$db->re} ON {\$db->le}{$table["name"]}{\$db->re}.{\$db->le}{$reference["local"]}{\$db->re}={\$db->le}{$reftable}{\$db->re}.{\$db->le}{$reference["foreign"]}{\$db->re}\","; 
			}
		}
		
		if ($joinParentLookups) {
			$child = $table;
			while (isset($child)) {
				$parent = self::$tables[$child["parent"]];
				if (isset($parent["references"])) {
					foreach ($parent["references"] as $reftable=>$reference) {
						if (in_array($reference["local"], $parent["primary_keys"])) {continue;}
						$lines[] = TAB . TAB . TAB . "\"LEFT JOIN {\$db->le}{$reftable}{\$db->re} ON {\$db->le}{$parent["name"]}{\$db->re}.{\$db->le}{$reference["local"]}{\$db->re}={\$db->le}{$reftable}{\$db->re}.{\$db->le}{$reference["foreign"]}{\$db->re}\","; 
					}
				}
				$child = $parent["is_child"] ? $parent : null;
			}
		}
		
		return implode(NL, $lines);
	}
	
	private static function setParentJoinCondition($table) {
		$lines = array();
		
		$lines[] = TAB . TAB . "if (isset(\$id)) {"; 
		$lines[] = TAB . TAB . TAB . "\$condition = \"{\$db->le}{$table["name"]}{\$db->re}.{\$db->le}{$table["id_column"]}{\$db->re}={\$db->queryValue(\$id)}\";"; 
		$lines[] = TAB . TAB . "}"; 
		if ($table["type"]==="RELATIONSHIP") {
			$object1 = $table["columns"][$table["primary_keys"][0]]["property"];
			$object2 = $table["columns"][$table["primary_keys"][1]]["property"];
			$relType = $table["columns"][$table["primary_keys"][2]]["property"];
			$lines[count($lines)-1] .= " else if (isset(\${$object1}) && isset(\${$object2})) {";
			$lines[] = TAB . TAB . TAB . "\$condition = \"{\$db->le}{$table["primary_keys"][0]}{\$db->re}={\$db->queryValue(\${$object1})} AND {\$db->le}{$table["primary_keys"][1]}{\$db->re}={\$db->queryValue(\${$object2})} AND {\$db->le}{$table["primary_keys"][2]}{\$db->re}={\$db->queryValue(\${$relType})}\";";
			$lines[] = TAB . TAB . "}";
		} else {
			foreach ($table["collapsed_unique_keys"] as $key) {
				$key = is_array($key) ? $key[0] : $key;
				if ($key==="_id") {continue;}
				$child = $table;
				while (!isset($child["columns"][$key])) {
					$child = self::$tables[$child["parent"]];
				}
				$prop = $child["columns"][$key]["property"];
				$lines[count($lines)-1] .= " else if (isset(\${$prop})) {";
				$lines[] = TAB . TAB . TAB . "\$condition = \"{\$db->le}{$child["name"]}{\$db->re}.{\$db->le}{$prop}{\$db->re}={\$db->queryValue(\${$prop})}\";";
				$lines[] = TAB . TAB . "}";
			}
		}
		
		return implode(NL, $lines);
	}
	
	private static function setSnapshotCondition($table) {
		$lines = array();
		
		$lines[] = TAB . TAB . TAB . "\$condition .= \" AND ({\$db->le}{$table["name"]}{\$db->re}.{\$db->le}tdate{\$db->re} IS NOT NULL AND {\$db->le}{$table["name"]}{\$db->re}.{\$db->le}fdate{\$db->re}<={\$db->queryValue(\$date)} AND {\$db->queryValue(\$date)}<={\$db->le}{$table["name"]}{\$db->re}.{\$db->le}tdate{\$db->re}) \";";
		$child = $table;
		while ($child["is_child"]) {
			$parent = self::$tables[$child["parent"]];
			$lines[] = TAB . TAB . TAB . "\$condition .= \" AND ({\$db->le}{$parent["name"]}{\$db->re}.{\$db->le}tdate{\$db->re} IS NOT NULL AND {\$db->le}{$parent["name"]}{\$db->re}.{\$db->le}fdate{\$db->re}<={\$db->queryValue(\$date)} AND {\$db->queryValue(\$date)}<={\$db->le}{$parent["name"]}{\$db->re}.{\$db->le}tdate{\$db->re}) \";";
			$child = $parent;
		}
		
		return implode(NL, $lines);
	}
	
	private static function joinParentTables($table, $addCondition=true) {
		$lines = array();
		
		$child = $table;
		while (isset($child)) {
			$parent = self::$tables[$child["parent"]];
			$lines[] = TAB . TAB . TAB . "\"INNER JOIN {\$db->le}{$child["parent"]}{\$db->re} ON {\$db->le}{$child["name"]}{\$db->re}.{\$db->le}{$child["primary_key"]}{\$db->re}={\$db->le}{$parent["name"]}{\$db->re}.{\$db->le}{$parent["primary_key"]}{\$db->re}";
			if ($addCondition && !$parent["is_child"]) {
				$lines[count($lines)-1] .= " AND {\$condition}";
			}
			$lines[count($lines)-1] .= "\",";
			$child = $parent["is_child"] ? $parent : null;
		}
		if (count($lines)>0) {
			$lines[count($lines)-1] = substr($lines[count($lines)-1], 0, -1);
		}
		
		return implode(NL, $lines);
	}
	
	private static function setPropertyDefaults($table) {
		$lines = array();
		
		foreach ($table["nice_collapsed_columns"] as $colname=>$column) {
			if ($table["is_column_extension"] && $colname==="value") {
				$default = "\$value";
			} else {
				$type = is_array($column["type"]) ? $column["type"][0] : $column["type"];
				$d = $column["default"];
				if (isset($column["references"]) && self::$tables[$column["references"][0]]["is_lookup"]) {
					$d = 1;
				} else if ($type==="tinyint" || $type==="bit") {
					$d = $d===0 ? false : true;
				}
				
				if ($column["is_unique"] || $column["is_primary"]) {
					if ($column["property"]==="hash") {
						$default = "isset(\${$column["property"]}) ? \${$column["property"]} : uniqid()";
					} else {
						$default = "\${$column["property"]}";
					}
				} else {
					$default = json_encode($d);
				}
			}
			$lines[] = TAB . TAB . "\$this->{$column["property"]} = " . (preg_match('/^date|time|timestamp|datetime|year$/i',$type) ? "new Date()" : $default) . ";";
		}
		
		return implode(NL, $lines);
	}
	
	private static function defineProperties($table) {
		$lines = array();
		
		foreach ($table["nice_columns"] as $colname=>$column) {
			if ($colname==="id" && $table["is_child"]) {continue;}
			$lines[] = TAB . "public \${$column["property"]};";
		}
		
		return implode(NL, $lines);
	}
	
	private static function listConstructorArgs($table, $withDefaults=false, $forLogDatabase=false) {
		$text = "";
		
		$defaultText = $withDefaults ? "=null" : "";
		$args = array();
		foreach ($table["primary_keys"] as $primaryKey) {
			$args[] = "\$" . $table["columns"][$primaryKey]["property"] . ($primaryKey==="{$table["name"]}_type_id" && $withDefaults ? "=1" : $defaultText);
		}
		$primaryKeyArgs = implode(", ", $args);
		if ($table["type"]==="RELATIONSHIP") {
			$text .= "\$id{$defaultText}, {$primaryKeyArgs}";
		} else {
			$text .= "\$id{$defaultText}";
			foreach ($table["collapsed_unique_keys"] as $key) {
				if ($key==="_id") {continue;}
				$col = is_array($key) ? $key[0] : $key;
				$child = $table;
				while (!isset($child["columns"][$col])) {
					$child = self::$tables[$child["parent"]];
				}
				$text .= ", \${$child["columns"][$col]["property"]}{$defaultText}";
			}
			
			if ($withDefaults && $table["is_column_extension"] && !$forLogDatabase) {
				$text .= ", \$value=null";
			}
		}
		
		return $text;
	}
	
	public static function writeMySQL($writeLogSchema=false) {
		$lines = array();
		
		$lines[] = "SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;";
		$lines[] = "SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';";
		$lines[] = "";
		
		foreach (self::$tables as $tablename=>$table) {
			$lines[] = "DROP TABLE IF EXISTS `{$tablename}`;";
			$lines[] = "CREATE TABLE `{$tablename}` (";
			
			if ($writeLogSchema) {
				$lines[] = "  `log_sequence` INT NOT NULL AUTO_INCREMENT,";
				$lines[] = "  `fdate` DATETIME NOT NULL,";
				$lines[] = "  `tdate` DATETIME NULL DEFAULT NULL,";
			}
			
			foreach ($table["columns"] as $colname=>$column) {
				$type = strtoupper(is_array($column["type"]) ? $column["type"][0] . "(" . implode(",",array_slice($column["type"],1)) . ")" : $column["type"]);
				$nullstring = isset($column["nullable"]) ? ($column["nullable"] ? "NULL" : "NOT NULL") : "NULL";
				if (isset($column["references"]) && self::$tables[$column["references"][0]]["is_lookup"]) {
					$def = 1;
				} else {
					$def = $column["default"];
				}
				$defaultstring = isset($def) ? (is_string($def) ? " DEFAULT '{$def}'" : " DEFAULT {$def}") : "";
				$autostring = $column["auto"] && !$writeLogSchema ? " AUTO_INCREMENT" : "";
				$lines[] = "  `{$colname}` {$type} {$nullstring}{$defaultstring}{$autostring},";
			}
			
			if ($writeLogSchema) {
				$lines[] = "  PRIMARY KEY (`log_sequence`)";
			} else {
				$lines[] = "  PRIMARY KEY (`" . implode("`,`",$table["primary_keys"]) . "`)";
				if (isset($table["unique_keys"])) {
					$lines[count($lines)-1] .= ",";
					foreach ($table["unique_keys"] as $key) {
						$lines[] = "  UNIQUE KEY (`" . (is_array($key) ? "{$key[0]}`({$key[1]}))," : "{$key}`),");
					}
					$lines[count($lines)-1] = substr($lines[count($lines)-1], 0, -1);
				}
				if (isset($table["references"])) {
					$lines[count($lines)-1] .= ",";
					foreach ($table["references"] as $reftable=>$reference) {
						if ($table["is_self_referential"] && ($reftable==="child" || $reftable==="parent")) {
							$reftable = $table["parts"][0];
						}
						$lines[] = "  FOREIGN KEY (`{$reference["local"]}`) REFERENCES `{$reftable}`(`{$reference["foreign"]}`),";
					}
					$lines[count($lines)-1] = substr($lines[count($lines)-1], 0, -1);
				}
			}
			
			$lines[] = ") ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
			$lines[] = "";
		}
		$lines[] = "SET SQL_MODE=@OLD_SQL_MODE;";
		$lines[] = "SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;";
		$text = implode(NL, $lines);
		file_put_contents(Helix::$path . "/temp/helix." . ($writeLogSchema ? "log-" : "") . "schema.mysql.sql",$text);
	}
	
	public static function writeMySQLInserts() {
		$lines = array();
		
		$lines[] = "SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;";
		$lines[] = "SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';";
		$lines[] = "";
		
		foreach (self::$tables as $tablename=>$table) {
			if (isset($table["data"]) && isset($table["data"]["values"]) && count($table["data"]["values"])>0) {
				$lines[] = "DELETE FROM `{$tablename}`;";
				$lines[] = "ALTER TABLE `{$tablename}` AUTO_INCREMENT=1;";
				$lines[] = "INSERT INTO `{$tablename}` (`" . implode("`,`",$table["data"]["columns"]) . "`) VALUES ";
				foreach ($table["data"]["values"] as $values) {
					foreach ($values as &$value) {
						$value = str_replace("'", "\\'", str_replace("\\", "\\\\", $value));
					}
					$lines[] = "('" . implode("','", $values) . "'),";
				}
				$lines[count($lines)-1] = substr($lines[count($lines)-1], 0, -1) . ";";
				$lines[] = "";
			}
		}
		$lines[] = "SET SQL_MODE=@OLD_SQL_MODE;";
		$lines[] = "SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;";
		$text = implode(NL, $lines);
		file_put_contents(Helix::$path . "/temp/helix.data.mysql.sql",$text);
	}
	
	public static function writeMSSQL() {
		$lines = array();
		foreach (self::$tables as $tablename=>$table) {
			$lines[] = "IF OBJECT_ID('dbo.[{$tablename}]','U') IS NOT NULL DROP TABLE dbo.[{$tablename}];";
			$lines[] = "CREATE TABLE [{$tablename}] (";
			foreach ($table["columns"] as $colname=>$column) {
				$typeName = self::$types[strtoupper(is_array($column["type"]) ? $column["type"][0] : $column["type"])]["mssql"];
				$type = strtoupper(is_array($column["type"]) ? $typeName . "(" . implode(",",array_slice($column["type"],1)) . ")" : $typeName);
				if (isset($column["key"])) {
					if ((is_array($column["key"]) && $column["key"][0]==="unique") || $column["key"]==="unique") {
						$type = str_replace("VARCHAR(MAX)","VARCHAR(900)",$type);
					}
				}
				$nullstring = isset($column["nullable"]) ? ($column["nullable"] ? "NULL" : "NOT NULL") : "NULL";
				$def = $column["default"];
				$defaultstring = isset($def) ? (is_string($def) ? " DEFAULT '{$def}'" : " DEFAULT {$def}") : "";
				$autostring = $column["auto"] ? " IDENTITY(1,1)" : "";
				$lines[] = "  [{$colname}] {$type} {$nullstring}{$defaultstring}{$autostring},";
			}
			$lines[] = "  PRIMARY KEY ([" . implode("],[",$table["primary_keys"]) . "])";
			if (isset($table["unique_keys"])) {
				$lines[count($lines)-1] .= ",";
				foreach ($table["unique_keys"] as $key) {
					$lines[] = "  UNIQUE ([" . (is_array($key) ? "{$key[0]}])," : "{$key}]),");
				}
				$lines[count($lines)-1] = substr($lines[count($lines)-1], 0, -1);
			}
			/*
			if (isset($table["references"])) {
				$lines[count($lines)-1] .= ",";
				foreach ($table["references"] as $reftable=>$reference) {
					$lines[] = "  FOREIGN KEY ([{$reference["local"]}]) REFERENCES [{$reftable}]([{$reference["foreign"]}]),";
				}
				$lines[count($lines)-1] = substr($lines[count($lines)-1], 0, -1);
			}
			*/
			$lines[] = ");";
			$lines[] = "";
		}
		$text = implode(NL, $lines);
		file_put_contents(Helix::$path . "/temp/helix.schema.mssql.sql",$text);
	}
	
	public static function writeSqlite() {
        $lines = array();
        
        foreach (self::$tables as $tablename=>$table) {
            $lines[] = "DROP TABLE IF EXISTS `{$tablename}`;";
            $lines[] = "CREATE TABLE `{$tablename}` (";
            foreach ($table["columns"] as $colname=>$column) {
                $type = strtoupper(is_array($column["type"]) ? $column["type"][0] . "(" . implode(",",array_slice($column["type"],1)) . ")" : $column["type"]);
                $nullstring = isset($column["nullable"]) ? ($column["nullable"] ? "NULL" : "NOT NULL") : "NULL";
                $lines[] = "  `{$colname}` {$type} {$nullstring},";
            }
            $lines[] = "  PRIMARY KEY (`" . implode("`,`",$table["primary_keys"]) . "`)";
            if (isset($table["unique_keys"])) {
                $lines[count($lines)-1] .= ",";
                foreach ($table["unique_keys"] as $key) {
                    $lines[] = "  UNIQUE (`" . (is_array($key) ? "{$key[0]}`)," : "{$key}`),");
                }
                $lines[count($lines)-1] = substr($lines[count($lines)-1], 0, -1);
            }
            if (isset($table["references"])) {
                $lines[count($lines)-1] .= ",";
                foreach ($table["references"] as $reftable=>$reference) {
					if ($table["is_self_referential"] && ($reftable==="child" || $reftable==="parent")) {
						$reftable = $table["parts"][0];
					}
                    $lines[] = "  FOREIGN KEY (`{$reference["local"]}`) REFERENCES `{$reftable}`(`{$reference["foreign"]}`),";
                }
                $lines[count($lines)-1] = substr($lines[count($lines)-1], 0, -1);
            }
            $lines[] = ");";
            $lines[] = "";
        }
        $text = implode(NL, $lines);
        file_put_contents(Helix::$path . "/temp/helix.schema.sqlite.sql",$text);
    }
	
	public static function getType(&$table) {
		$segments = explode("_",$table["name"]);
		if (count($segments)===1) {
			return "STANDARD";
		} else if (count($segments)===2) {
			if (strrchr($table["name"],"_")==="_type") {
				return "TYPE";
			} else if (preg_match('/_(ext|cus)$/i',$table["name"])>0) {
				return "STANDARD";
			} else {
				return "RELATIONSHIP";
			}
		} else if (count($segments)===3 && strrchr($table["name"],"_")==="_type") {
			return "RELATIONSHIP_TYPE";
		} else {
			return "";
		}
	}
	
	public static function getPHPType($type) {
		$type = is_array($type) ? $type[0] : $type;
		if (preg_match('/^smallint|mediumint|int|integer|bigint$/i',$type)>0) {
			return "int";
		} else if (preg_match('/^bit|tinyint$/i',$type)>0) {
			return "bool";
		} else if (preg_match('/^real|double|float|decimal|numeric$/i',$type)>0) {
			return "float";
		} else {
			return "string";
		}
	}
	
}
?>
