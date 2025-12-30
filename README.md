
```
perfilglobal_v2
├─ composer.json
├─ composer.lock
├─ perfilglobal.sql
├─ v1
│  ├─ archivo_prueba.xlsx
│  ├─ assets
│  │  ├─ css
│  │  │  └─ dashbd.css
│  │  └─ main
│  │     ├─ admindashboard.php
│  │     ├─ admin_01_cargar_bd.php
│  │     ├─ admin_02_registro_manual.php
│  │     ├─ admin_03_gestion_asistentes.php
│  │     ├─ buscar_asistente.php
│  │     ├─ crear_monitor.php
│  │     ├─ datos_opciones.php
│  │     ├─ editar_asistente.php
│  │     ├─ eliminar_asistente.php
│  │     ├─ get_asistente_por_cedula.php
│  │     ├─ guardar_nuevo_asistente.php
│  │     ├─ monitordashboard.php
│  │     ├─ monitor_01_crear_evento.php
│  │     ├─ monitor_02_registro_asistente.php
│  │     ├─ monitor_03_consultar_eventos.php
│  │     ├─ monitor_04_reportes_eventos.php
│  │     ├─ monitor_05_reporte_semestral.php
│  │     ├─ monitor_eliminar_asistencia.php
│  │     ├─ monitor_generar_reporte_evento_excel.php
│  │     ├─ monitor_generar_reporte_evento_pdf.php
│  │     ├─ monitor_generar_reporte_semestral_pdf.php
│  │     ├─ monitor_guardar_asistente.php
│  │     ├─ monitor_modificar_evento.php
│  │     ├─ monitor_registrar_asistente.php
│  │     ├─ monitor_registrar_asistente_form.php
│  │     ├─ monitor_registrar_invitado.php
│  │     ├─ monitor_resumen_asistencias.php
│  │     ├─ monitor_validar_cedula.php
│  │     ├─ monitor_ver_asistente.php
│  │     ├─ monitor_ver_evento.php
│  │     ├─ mostrar.mensaje.js
│  │     ├─ previsualizar_excel.php
│  │     ├─ procesar_sugerencias.php
│  │     ├─ registrar_asistente.php
│  │     ├─ registrar_invitado_guardar.php
│  │     ├─ sugerencias.php
│  │     ├─ verificar_eventos_invalidos.php
│  │     └─ ver_asitentes.php
│  ├─ componentes
│  │  ├─ fesc.jpg
│  │  ├─ footer copy.php
│  │  ├─ footer.php
│  │  └─ header.php
│  ├─ conexion.php
│  ├─ excel_prueba.php
│  ├─ index.php
│  ├─ info.php
│  ├─ login.php
│  ├─ logout.php
│  ├─ pdf_prueba.php
│  ├─ test.php
│  └─ uploads
│     ├─ 681c13e16832a_asistentes_corregido.xlsx
│     ├─ 681c157e94f9e_asistentes_corregido.xlsx
│     └─ 681c1ffee6010_asistentes_corregido.xlsx
├─ v2
│  ├─ config
│  │  ├─ .env
│  │  ├─ .env.example
│  │  ├─ db.php
│  │  └─ settings.php
│  ├─ controllers
│  │  ├─ AdminController.php
│  │  ├─ AuthController.php
│  │  ├─ MonitorController.php
│  │  └─ PasswordController.php
│  ├─ models
│  │  ├─ Registro.php
│  │  ├─ Token.php
│  │  └─ Usuario.php
│  ├─ public
│  │  ├─ .htaccess
│  │  ├─ assets
│  │  │  ├─ css
│  │  │  ├─ img
│  │  │  └─ js
│  │  └─ index.php
│  └─ views
│     ├─ admin
│     │  ├─ dashboard.php
│     │  └─ users-management.php
│     ├─ auth
│     │  ├─ forgot-password.php
│     │  ├─ login.php
│     │  └─ reset-password.php
│     ├─ layouts
│     │  ├─ footer.php
│     │  └─ header.php
│     └─ monitor
│        └─ dashboard.php
└─ vendor
   ├─ autoload.php
   ├─ bin
   ├─ composer
   │  ├─ autoload_classmap.php
   │  ├─ autoload_namespaces.php
   │  ├─ autoload_psr4.php
   │  ├─ autoload_real.php
   │  ├─ autoload_static.php
   │  ├─ ClassLoader.php
   │  ├─ installed.json
   │  ├─ installed.php
   │  ├─ InstalledVersions.php
   │  ├─ LICENSE
   │  ├─ pcre
   │  │  ├─ composer.json
   │  │  ├─ extension.neon
   │  │  ├─ LICENSE
   │  │  ├─ README.md
   │  │  └─ src
   │  │     ├─ MatchAllResult.php
   │  │     ├─ MatchAllStrictGroupsResult.php
   │  │     ├─ MatchAllWithOffsetsResult.php
   │  │     ├─ MatchResult.php
   │  │     ├─ MatchStrictGroupsResult.php
   │  │     ├─ MatchWithOffsetsResult.php
   │  │     ├─ PcreException.php
   │  │     ├─ PHPStan
   │  │     │  ├─ InvalidRegexPatternRule.php
   │  │     │  ├─ PregMatchFlags.php
   │  │     │  ├─ PregMatchParameterOutTypeExtension.php
   │  │     │  ├─ PregMatchTypeSpecifyingExtension.php
   │  │     │  ├─ PregReplaceCallbackClosureTypeExtension.php
   │  │     │  └─ UnsafeStrictGroupsCallRule.php
   │  │     ├─ Preg.php
   │  │     ├─ Regex.php
   │  │     ├─ ReplaceResult.php
   │  │     └─ UnexpectedNullMatchException.php
   │  └─ platform_check.php
   ├─ maennchen
   │  └─ zipstream-php
   │     ├─ .editorconfig
   │     ├─ .phive
   │     │  └─ phars.xml
   │     ├─ .php-cs-fixer.dist.php
   │     ├─ .phpdoc
   │     │  └─ template
   │     │     └─ base.html.twig
   │     ├─ .tool-versions
   │     ├─ composer.json
   │     ├─ guides
   │     │  ├─ ContentLength.rst
   │     │  ├─ FlySystem.rst
   │     │  ├─ index.rst
   │     │  ├─ Nginx.rst
   │     │  ├─ Options.rst
   │     │  ├─ PSR7Streams.rst
   │     │  ├─ StreamOutput.rst
   │     │  ├─ Symfony.rst
   │     │  └─ Varnish.rst
   │     ├─ LICENSE
   │     ├─ phpdoc.dist.xml
   │     ├─ phpunit.xml.dist
   │     ├─ psalm.xml
   │     ├─ README.md
   │     ├─ src
   │     │  ├─ CentralDirectoryFileHeader.php
   │     │  ├─ CompressionMethod.php
   │     │  ├─ DataDescriptor.php
   │     │  ├─ EndOfCentralDirectory.php
   │     │  ├─ Exception
   │     │  │  ├─ DosTimeOverflowException.php
   │     │  │  ├─ FileNotFoundException.php
   │     │  │  ├─ FileNotReadableException.php
   │     │  │  ├─ FileSizeIncorrectException.php
   │     │  │  ├─ OverflowException.php
   │     │  │  ├─ ResourceActionException.php
   │     │  │  ├─ SimulationFileUnknownException.php
   │     │  │  ├─ StreamNotReadableException.php
   │     │  │  └─ StreamNotSeekableException.php
   │     │  ├─ Exception.php
   │     │  ├─ File.php
   │     │  ├─ GeneralPurposeBitFlag.php
   │     │  ├─ LocalFileHeader.php
   │     │  ├─ OperationMode.php
   │     │  ├─ PackField.php
   │     │  ├─ Time.php
   │     │  ├─ Version.php
   │     │  ├─ Zip64
   │     │  │  ├─ DataDescriptor.php
   │     │  │  ├─ EndOfCentralDirectory.php
   │     │  │  ├─ EndOfCentralDirectoryLocator.php
   │     │  │  └─ ExtendedInformationExtraField.php
   │     │  ├─ ZipStream.php
   │     │  └─ Zs
   │     │     └─ ExtendedInformationExtraField.php
   │     └─ test
   │        ├─ Assertions.php
   │        ├─ bootstrap.php
   │        ├─ CentralDirectoryFileHeaderTest.php
   │        ├─ DataDescriptorTest.php
   │        ├─ EndlessCycleStream.php
   │        ├─ EndOfCentralDirectoryTest.php
   │        ├─ FaultInjectionResource.php
   │        ├─ LocalFileHeaderTest.php
   │        ├─ PackFieldTest.php
   │        ├─ ResourceStream.php
   │        ├─ Tempfile.php
   │        ├─ TimeTest.php
   │        ├─ Util.php
   │        ├─ Zip64
   │        │  ├─ DataDescriptorTest.php
   │        │  ├─ EndOfCentralDirectoryLocatorTest.php
   │        │  ├─ EndOfCentralDirectoryTest.php
   │        │  └─ ExtendedInformationExtraFieldTest.php
   │        ├─ ZipStreamTest.php
   │        └─ Zs
   │           └─ ExtendedInformationExtraFieldTest.php
   ├─ markbaker
   │  ├─ complex
   │  │  ├─ classes
   │  │  │  └─ src
   │  │  │     ├─ Complex.php
   │  │  │     ├─ Exception.php
   │  │  │     ├─ Functions.php
   │  │  │     └─ Operations.php
   │  │  ├─ composer.json
   │  │  ├─ examples
   │  │  │  ├─ complexTest.php
   │  │  │  ├─ testFunctions.php
   │  │  │  └─ testOperations.php
   │  │  ├─ license.md
   │  │  └─ README.md
   │  └─ matrix
   │     ├─ buildPhar.php
   │     ├─ classes
   │     │  └─ src
   │     │     ├─ Builder.php
   │     │     ├─ Decomposition
   │     │     │  ├─ Decomposition.php
   │     │     │  ├─ LU.php
   │     │     │  └─ QR.php
   │     │     ├─ Div0Exception.php
   │     │     ├─ Exception.php
   │     │     ├─ Functions.php
   │     │     ├─ Matrix.php
   │     │     ├─ Operations.php
   │     │     └─ Operators
   │     │        ├─ Addition.php
   │     │        ├─ DirectSum.php
   │     │        ├─ Division.php
   │     │        ├─ Multiplication.php
   │     │        ├─ Operator.php
   │     │        └─ Subtraction.php
   │     ├─ composer.json
   │     ├─ examples
   │     │  └─ test.php
   │     ├─ infection.json.dist
   │     ├─ license.md
   │     ├─ phpstan.neon
   │     └─ README.md
   ├─ phpoffice
   │  └─ phpspreadsheet
   │     ├─ CHANGELOG.md
   │     ├─ composer.json
   │     ├─ CONTRIBUTING.md
   │     ├─ LICENSE
   │     ├─ README.md
   │     └─ src
   │        └─ PhpSpreadsheet
   │           ├─ Calculation
   │           │  ├─ ArrayEnabled.php
   │           │  ├─ BinaryComparison.php
   │           │  ├─ Calculation.php
   │           │  ├─ CalculationBase.php
   │           │  ├─ CalculationLocale.php
   │           │  ├─ Category.php
   │           │  ├─ Database
   │           │  │  ├─ DatabaseAbstract.php
   │           │  │  ├─ DAverage.php
   │           │  │  ├─ DCount.php
   │           │  │  ├─ DCountA.php
   │           │  │  ├─ DGet.php
   │           │  │  ├─ DMax.php
   │           │  │  ├─ DMin.php
   │           │  │  ├─ DProduct.php
   │           │  │  ├─ DStDev.php
   │           │  │  ├─ DStDevP.php
   │           │  │  ├─ DSum.php
   │           │  │  ├─ DVar.php
   │           │  │  └─ DVarP.php
   │           │  ├─ DateTimeExcel
   │           │  │  ├─ Constants.php
   │           │  │  ├─ Current.php
   │           │  │  ├─ Date.php
   │           │  │  ├─ DateParts.php
   │           │  │  ├─ DateValue.php
   │           │  │  ├─ Days.php
   │           │  │  ├─ Days360.php
   │           │  │  ├─ Difference.php
   │           │  │  ├─ Helpers.php
   │           │  │  ├─ Month.php
   │           │  │  ├─ NetworkDays.php
   │           │  │  ├─ Time.php
   │           │  │  ├─ TimeParts.php
   │           │  │  ├─ TimeValue.php
   │           │  │  ├─ Week.php
   │           │  │  ├─ WorkDay.php
   │           │  │  └─ YearFrac.php
   │           │  ├─ Engine
   │           │  │  ├─ ArrayArgumentHelper.php
   │           │  │  ├─ ArrayArgumentProcessor.php
   │           │  │  ├─ BranchPruner.php
   │           │  │  ├─ CyclicReferenceStack.php
   │           │  │  ├─ FormattedNumber.php
   │           │  │  ├─ Logger.php
   │           │  │  └─ Operands
   │           │  │     ├─ Operand.php
   │           │  │     └─ StructuredReference.php
   │           │  ├─ Engineering
   │           │  │  ├─ BesselI.php
   │           │  │  ├─ BesselJ.php
   │           │  │  ├─ BesselK.php
   │           │  │  ├─ BesselY.php
   │           │  │  ├─ BitWise.php
   │           │  │  ├─ Compare.php
   │           │  │  ├─ Complex.php
   │           │  │  ├─ ComplexFunctions.php
   │           │  │  ├─ ComplexOperations.php
   │           │  │  ├─ Constants.php
   │           │  │  ├─ ConvertBase.php
   │           │  │  ├─ ConvertBinary.php
   │           │  │  ├─ ConvertDecimal.php
   │           │  │  ├─ ConvertHex.php
   │           │  │  ├─ ConvertOctal.php
   │           │  │  ├─ ConvertUOM.php
   │           │  │  ├─ EngineeringValidations.php
   │           │  │  ├─ Erf.php
   │           │  │  └─ ErfC.php
   │           │  ├─ Exception.php
   │           │  ├─ ExceptionHandler.php
   │           │  ├─ Financial
   │           │  │  ├─ Amortization.php
   │           │  │  ├─ CashFlow
   │           │  │  │  ├─ CashFlowValidations.php
   │           │  │  │  ├─ Constant
   │           │  │  │  │  ├─ Periodic
   │           │  │  │  │  │  ├─ Cumulative.php
   │           │  │  │  │  │  ├─ Interest.php
   │           │  │  │  │  │  ├─ InterestAndPrincipal.php
   │           │  │  │  │  │  └─ Payments.php
   │           │  │  │  │  └─ Periodic.php
   │           │  │  │  ├─ Single.php
   │           │  │  │  └─ Variable
   │           │  │  │     ├─ NonPeriodic.php
   │           │  │  │     └─ Periodic.php
   │           │  │  ├─ Constants.php
   │           │  │  ├─ Coupons.php
   │           │  │  ├─ Depreciation.php
   │           │  │  ├─ Dollar.php
   │           │  │  ├─ FinancialValidations.php
   │           │  │  ├─ Helpers.php
   │           │  │  ├─ InterestRate.php
   │           │  │  ├─ Securities
   │           │  │  │  ├─ AccruedInterest.php
   │           │  │  │  ├─ Price.php
   │           │  │  │  ├─ Rates.php
   │           │  │  │  ├─ SecurityValidations.php
   │           │  │  │  └─ Yields.php
   │           │  │  └─ TreasuryBill.php
   │           │  ├─ FormulaParser.php
   │           │  ├─ FormulaToken.php
   │           │  ├─ FunctionArray.php
   │           │  ├─ Functions.php
   │           │  ├─ Information
   │           │  │  ├─ ErrorValue.php
   │           │  │  ├─ ExcelError.php
   │           │  │  └─ Value.php
   │           │  ├─ Internal
   │           │  │  ├─ ExcelArrayPseudoFunctions.php
   │           │  │  ├─ MakeMatrix.php
   │           │  │  └─ WildcardMatch.php
   │           │  ├─ locale
   │           │  │  ├─ bg
   │           │  │  │  ├─ config
   │           │  │  │  └─ functions
   │           │  │  ├─ cs
   │           │  │  │  ├─ config
   │           │  │  │  └─ functions
   │           │  │  ├─ da
   │           │  │  │  ├─ config
   │           │  │  │  └─ functions
   │           │  │  ├─ de
   │           │  │  │  ├─ config
   │           │  │  │  └─ functions
   │           │  │  ├─ en
   │           │  │  │  └─ uk
   │           │  │  │     └─ config
   │           │  │  ├─ es
   │           │  │  │  ├─ config
   │           │  │  │  └─ functions
   │           │  │  ├─ fi
   │           │  │  │  ├─ config
   │           │  │  │  └─ functions
   │           │  │  ├─ fr
   │           │  │  │  ├─ config
   │           │  │  │  └─ functions
   │           │  │  ├─ hu
   │           │  │  │  ├─ config
   │           │  │  │  └─ functions
   │           │  │  ├─ it
   │           │  │  │  ├─ config
   │           │  │  │  └─ functions
   │           │  │  ├─ nb
   │           │  │  │  ├─ config
   │           │  │  │  └─ functions
   │           │  │  ├─ nl
   │           │  │  │  ├─ config
   │           │  │  │  └─ functions
   │           │  │  ├─ pl
   │           │  │  │  ├─ config
   │           │  │  │  └─ functions
   │           │  │  ├─ pt
   │           │  │  │  ├─ br
   │           │  │  │  │  ├─ config
   │           │  │  │  │  └─ functions
   │           │  │  │  ├─ config
   │           │  │  │  └─ functions
   │           │  │  ├─ ru
   │           │  │  │  ├─ config
   │           │  │  │  └─ functions
   │           │  │  ├─ sv
   │           │  │  │  ├─ config
   │           │  │  │  └─ functions
   │           │  │  ├─ tr
   │           │  │  │  ├─ config
   │           │  │  │  └─ functions
   │           │  │  └─ Translations.xlsx
   │           │  ├─ Logical
   │           │  │  ├─ Boolean.php
   │           │  │  ├─ Conditional.php
   │           │  │  └─ Operations.php
   │           │  ├─ LookupRef
   │           │  │  ├─ Address.php
   │           │  │  ├─ ChooseRowsEtc.php
   │           │  │  ├─ ExcelMatch.php
   │           │  │  ├─ Filter.php
   │           │  │  ├─ Formula.php
   │           │  │  ├─ Helpers.php
   │           │  │  ├─ HLookup.php
   │           │  │  ├─ Hyperlink.php
   │           │  │  ├─ Indirect.php
   │           │  │  ├─ Lookup.php
   │           │  │  ├─ LookupBase.php
   │           │  │  ├─ LookupRefValidations.php
   │           │  │  ├─ Matrix.php
   │           │  │  ├─ Offset.php
   │           │  │  ├─ RowColumnInformation.php
   │           │  │  ├─ Selection.php
   │           │  │  ├─ Sort.php
   │           │  │  ├─ Unique.php
   │           │  │  └─ VLookup.php
   │           │  ├─ MathTrig
   │           │  │  ├─ Absolute.php
   │           │  │  ├─ Angle.php
   │           │  │  ├─ Arabic.php
   │           │  │  ├─ Base.php
   │           │  │  ├─ Ceiling.php
   │           │  │  ├─ Combinations.php
   │           │  │  ├─ Exp.php
   │           │  │  ├─ Factorial.php
   │           │  │  ├─ Floor.php
   │           │  │  ├─ Gcd.php
   │           │  │  ├─ Helpers.php
   │           │  │  ├─ IntClass.php
   │           │  │  ├─ Lcm.php
   │           │  │  ├─ Logarithms.php
   │           │  │  ├─ MatrixFunctions.php
   │           │  │  ├─ Operations.php
   │           │  │  ├─ Random.php
   │           │  │  ├─ Roman.php
   │           │  │  ├─ Round.php
   │           │  │  ├─ SeriesSum.php
   │           │  │  ├─ Sign.php
   │           │  │  ├─ Sqrt.php
   │           │  │  ├─ Subtotal.php
   │           │  │  ├─ Sum.php
   │           │  │  ├─ SumSquares.php
   │           │  │  ├─ Trig
   │           │  │  │  ├─ Cosecant.php
   │           │  │  │  ├─ Cosine.php
   │           │  │  │  ├─ Cotangent.php
   │           │  │  │  ├─ Secant.php
   │           │  │  │  ├─ Sine.php
   │           │  │  │  └─ Tangent.php
   │           │  │  └─ Trunc.php
   │           │  ├─ Statistical
   │           │  │  ├─ AggregateBase.php
   │           │  │  ├─ Averages
   │           │  │  │  └─ Mean.php
   │           │  │  ├─ Averages.php
   │           │  │  ├─ Conditional.php
   │           │  │  ├─ Confidence.php
   │           │  │  ├─ Counts.php
   │           │  │  ├─ Deviations.php
   │           │  │  ├─ Distributions
   │           │  │  │  ├─ Beta.php
   │           │  │  │  ├─ Binomial.php
   │           │  │  │  ├─ ChiSquared.php
   │           │  │  │  ├─ DistributionValidations.php
   │           │  │  │  ├─ Exponential.php
   │           │  │  │  ├─ F.php
   │           │  │  │  ├─ Fisher.php
   │           │  │  │  ├─ Gamma.php
   │           │  │  │  ├─ GammaBase.php
   │           │  │  │  ├─ HyperGeometric.php
   │           │  │  │  ├─ LogNormal.php
   │           │  │  │  ├─ NewtonRaphson.php
   │           │  │  │  ├─ Normal.php
   │           │  │  │  ├─ Poisson.php
   │           │  │  │  ├─ StandardNormal.php
   │           │  │  │  ├─ StudentT.php
   │           │  │  │  └─ Weibull.php
   │           │  │  ├─ Maximum.php
   │           │  │  ├─ MaxMinBase.php
   │           │  │  ├─ Minimum.php
   │           │  │  ├─ Percentiles.php
   │           │  │  ├─ Permutations.php
   │           │  │  ├─ Size.php
   │           │  │  ├─ StandardDeviations.php
   │           │  │  ├─ Standardize.php
   │           │  │  ├─ StatisticalValidations.php
   │           │  │  ├─ Trends.php
   │           │  │  ├─ VarianceBase.php
   │           │  │  └─ Variances.php
   │           │  ├─ TextData
   │           │  │  ├─ CaseConvert.php
   │           │  │  ├─ CharacterConvert.php
   │           │  │  ├─ Concatenate.php
   │           │  │  ├─ Extract.php
   │           │  │  ├─ Format.php
   │           │  │  ├─ Helpers.php
   │           │  │  ├─ Replace.php
   │           │  │  ├─ Search.php
   │           │  │  ├─ Text.php
   │           │  │  └─ Trim.php
   │           │  ├─ Token
   │           │  │  └─ Stack.php
   │           │  └─ Web
   │           │     └─ Service.php
   │           ├─ Cell
   │           │  ├─ AddressHelper.php
   │           │  ├─ AddressRange.php
   │           │  ├─ AdvancedValueBinder.php
   │           │  ├─ Cell.php
   │           │  ├─ CellAddress.php
   │           │  ├─ CellRange.php
   │           │  ├─ ColumnRange.php
   │           │  ├─ Coordinate.php
   │           │  ├─ DataType.php
   │           │  ├─ DataValidation.php
   │           │  ├─ DataValidator.php
   │           │  ├─ DefaultValueBinder.php
   │           │  ├─ Hyperlink.php
   │           │  ├─ IgnoredErrors.php
   │           │  ├─ IValueBinder.php
   │           │  ├─ RowRange.php
   │           │  └─ StringValueBinder.php
   │           ├─ CellReferenceHelper.php
   │           ├─ Chart
   │           │  ├─ Axis.php
   │           │  ├─ AxisText.php
   │           │  ├─ Chart.php
   │           │  ├─ ChartColor.php
   │           │  ├─ DataSeries.php
   │           │  ├─ DataSeriesValues.php
   │           │  ├─ Exception.php
   │           │  ├─ GridLines.php
   │           │  ├─ Layout.php
   │           │  ├─ Legend.php
   │           │  ├─ PlotArea.php
   │           │  ├─ Properties.php
   │           │  ├─ Renderer
   │           │  │  ├─ IRenderer.php
   │           │  │  ├─ JpGraph.php
   │           │  │  ├─ JpGraphRendererBase.php
   │           │  │  ├─ MtJpGraphRenderer.php
   │           │  │  └─ PHP Charting Libraries.txt
   │           │  ├─ Title.php
   │           │  └─ TrendLine.php
   │           ├─ Collection
   │           │  ├─ Cells.php
   │           │  ├─ CellsFactory.php
   │           │  └─ Memory
   │           │     ├─ SimpleCache1.php
   │           │     └─ SimpleCache3.php
   │           ├─ Comment.php
   │           ├─ DefinedName.php
   │           ├─ Document
   │           │  ├─ Properties.php
   │           │  └─ Security.php
   │           ├─ Exception.php
   │           ├─ HashTable.php
   │           ├─ Helper
   │           │  ├─ Dimension.php
   │           │  ├─ Downloader.php
   │           │  ├─ Handler.php
   │           │  ├─ Html.php
   │           │  ├─ Sample.php
   │           │  ├─ Size.php
   │           │  └─ TextGrid.php
   │           ├─ IComparable.php
   │           ├─ IOFactory.php
   │           ├─ NamedFormula.php
   │           ├─ NamedRange.php
   │           ├─ Reader
   │           │  ├─ BaseReader.php
   │           │  ├─ Csv
   │           │  │  └─ Delimiter.php
   │           │  ├─ Csv.php
   │           │  ├─ DefaultReadFilter.php
   │           │  ├─ Exception.php
   │           │  ├─ Gnumeric
   │           │  │  ├─ PageSetup.php
   │           │  │  ├─ Properties.php
   │           │  │  └─ Styles.php
   │           │  ├─ Gnumeric.php
   │           │  ├─ Html.php
   │           │  ├─ IReader.php
   │           │  ├─ IReadFilter.php
   │           │  ├─ Ods
   │           │  │  ├─ AutoFilter.php
   │           │  │  ├─ BaseLoader.php
   │           │  │  ├─ DefinedNames.php
   │           │  │  ├─ FormulaTranslator.php
   │           │  │  ├─ PageSettings.php
   │           │  │  └─ Properties.php
   │           │  ├─ Ods.php
   │           │  ├─ Security
   │           │  │  └─ XmlScanner.php
   │           │  ├─ Slk.php
   │           │  ├─ Xls
   │           │  │  ├─ Biff5.php
   │           │  │  ├─ Biff8.php
   │           │  │  ├─ Color
   │           │  │  │  ├─ BIFF5.php
   │           │  │  │  ├─ BIFF8.php
   │           │  │  │  └─ BuiltIn.php
   │           │  │  ├─ Color.php
   │           │  │  ├─ ConditionalFormatting.php
   │           │  │  ├─ DataValidationHelper.php
   │           │  │  ├─ ErrorCode.php
   │           │  │  ├─ Escher.php
   │           │  │  ├─ ListFunctions.php
   │           │  │  ├─ LoadSpreadsheet.php
   │           │  │  ├─ Mappings.php
   │           │  │  ├─ MD5.php
   │           │  │  ├─ RC4.php
   │           │  │  └─ Style
   │           │  │     ├─ Border.php
   │           │  │     ├─ CellAlignment.php
   │           │  │     ├─ CellFont.php
   │           │  │     └─ FillPattern.php
   │           │  ├─ Xls.php
   │           │  ├─ XlsBase.php
   │           │  ├─ Xlsx
   │           │  │  ├─ AutoFilter.php
   │           │  │  ├─ BaseParserClass.php
   │           │  │  ├─ Chart.php
   │           │  │  ├─ ColumnAndRowAttributes.php
   │           │  │  ├─ ConditionalStyles.php
   │           │  │  ├─ DataValidations.php
   │           │  │  ├─ Hyperlinks.php
   │           │  │  ├─ Namespaces.php
   │           │  │  ├─ PageSetup.php
   │           │  │  ├─ Properties.php
   │           │  │  ├─ SharedFormula.php
   │           │  │  ├─ SheetViewOptions.php
   │           │  │  ├─ SheetViews.php
   │           │  │  ├─ Styles.php
   │           │  │  ├─ TableReader.php
   │           │  │  ├─ Theme.php
   │           │  │  └─ WorkbookView.php
   │           │  ├─ Xlsx.php
   │           │  ├─ Xml
   │           │  │  ├─ DataValidations.php
   │           │  │  ├─ PageSettings.php
   │           │  │  ├─ Properties.php
   │           │  │  ├─ Style
   │           │  │  │  ├─ Alignment.php
   │           │  │  │  ├─ Border.php
   │           │  │  │  ├─ Fill.php
   │           │  │  │  ├─ Font.php
   │           │  │  │  ├─ NumberFormat.php
   │           │  │  │  └─ StyleBase.php
   │           │  │  └─ Style.php
   │           │  └─ Xml.php
   │           ├─ ReferenceHelper.php
   │           ├─ RichText
   │           │  ├─ ITextElement.php
   │           │  ├─ RichText.php
   │           │  ├─ Run.php
   │           │  └─ TextElement.php
   │           ├─ Settings.php
   │           ├─ Shared
   │           │  ├─ CodePage.php
   │           │  ├─ Date.php
   │           │  ├─ Drawing.php
   │           │  ├─ Escher
   │           │  │  ├─ DgContainer
   │           │  │  │  ├─ SpgrContainer
   │           │  │  │  │  └─ SpContainer.php
   │           │  │  │  └─ SpgrContainer.php
   │           │  │  ├─ DgContainer.php
   │           │  │  ├─ DggContainer
   │           │  │  │  ├─ BstoreContainer
   │           │  │  │  │  ├─ BSE
   │           │  │  │  │  │  └─ Blip.php
   │           │  │  │  │  └─ BSE.php
   │           │  │  │  └─ BstoreContainer.php
   │           │  │  └─ DggContainer.php
   │           │  ├─ Escher.php
   │           │  ├─ File.php
   │           │  ├─ Font.php
   │           │  ├─ IntOrFloat.php
   │           │  ├─ OLE
   │           │  │  ├─ ChainedBlockStream.php
   │           │  │  ├─ PPS
   │           │  │  │  ├─ File.php
   │           │  │  │  └─ Root.php
   │           │  │  └─ PPS.php
   │           │  ├─ OLE.php
   │           │  ├─ OLERead.php
   │           │  ├─ PasswordHasher.php
   │           │  ├─ StringHelper.php
   │           │  ├─ TimeZone.php
   │           │  ├─ Trend
   │           │  │  ├─ BestFit.php
   │           │  │  ├─ ExponentialBestFit.php
   │           │  │  ├─ LinearBestFit.php
   │           │  │  ├─ LogarithmicBestFit.php
   │           │  │  ├─ PolynomialBestFit.php
   │           │  │  ├─ PowerBestFit.php
   │           │  │  └─ Trend.php
   │           │  ├─ Xls.php
   │           │  └─ XMLWriter.php
   │           ├─ Spreadsheet.php
   │           ├─ Style
   │           │  ├─ Alignment.php
   │           │  ├─ Border.php
   │           │  ├─ Borders.php
   │           │  ├─ Color.php
   │           │  ├─ Conditional.php
   │           │  ├─ ConditionalFormatting
   │           │  │  ├─ CellMatcher.php
   │           │  │  ├─ CellStyleAssessor.php
   │           │  │  ├─ ConditionalColorScale.php
   │           │  │  ├─ ConditionalDataBar.php
   │           │  │  ├─ ConditionalDataBarExtension.php
   │           │  │  ├─ ConditionalFormattingRuleExtension.php
   │           │  │  ├─ ConditionalFormatValueObject.php
   │           │  │  ├─ StyleMerger.php
   │           │  │  ├─ Wizard
   │           │  │  │  ├─ Blanks.php
   │           │  │  │  ├─ CellValue.php
   │           │  │  │  ├─ DateValue.php
   │           │  │  │  ├─ Duplicates.php
   │           │  │  │  ├─ Errors.php
   │           │  │  │  ├─ Expression.php
   │           │  │  │  ├─ TextValue.php
   │           │  │  │  ├─ WizardAbstract.php
   │           │  │  │  └─ WizardInterface.php
   │           │  │  └─ Wizard.php
   │           │  ├─ Fill.php
   │           │  ├─ Font.php
   │           │  ├─ NumberFormat
   │           │  │  ├─ BaseFormatter.php
   │           │  │  ├─ DateFormatter.php
   │           │  │  ├─ Formatter.php
   │           │  │  ├─ FractionFormatter.php
   │           │  │  ├─ NumberFormatter.php
   │           │  │  ├─ PercentageFormatter.php
   │           │  │  └─ Wizard
   │           │  │     ├─ Accounting.php
   │           │  │     ├─ Currency.php
   │           │  │     ├─ CurrencyBase.php
   │           │  │     ├─ CurrencyNegative.php
   │           │  │     ├─ Date.php
   │           │  │     ├─ DateTime.php
   │           │  │     ├─ DateTimeWizard.php
   │           │  │     ├─ Duration.php
   │           │  │     ├─ Locale.php
   │           │  │     ├─ Number.php
   │           │  │     ├─ NumberBase.php
   │           │  │     ├─ Percentage.php
   │           │  │     ├─ Scientific.php
   │           │  │     ├─ Time.php
   │           │  │     └─ Wizard.php
   │           │  ├─ NumberFormat.php
   │           │  ├─ Protection.php
   │           │  ├─ RgbTint.php
   │           │  ├─ Style.php
   │           │  └─ Supervisor.php
   │           ├─ Theme.php
   │           ├─ Worksheet
   │           │  ├─ AutoFilter
   │           │  │  ├─ Column
   │           │  │  │  └─ Rule.php
   │           │  │  └─ Column.php
   │           │  ├─ AutoFilter.php
   │           │  ├─ AutoFit.php
   │           │  ├─ BaseDrawing.php
   │           │  ├─ CellIterator.php
   │           │  ├─ Column.php
   │           │  ├─ ColumnCellIterator.php
   │           │  ├─ ColumnDimension.php
   │           │  ├─ ColumnIterator.php
   │           │  ├─ Dimension.php
   │           │  ├─ Drawing
   │           │  │  └─ Shadow.php
   │           │  ├─ Drawing.php
   │           │  ├─ HeaderFooter.php
   │           │  ├─ HeaderFooterDrawing.php
   │           │  ├─ Iterator.php
   │           │  ├─ MemoryDrawing.php
   │           │  ├─ PageBreak.php
   │           │  ├─ PageMargins.php
   │           │  ├─ PageSetup.php
   │           │  ├─ Pane.php
   │           │  ├─ ProtectedRange.php
   │           │  ├─ Protection.php
   │           │  ├─ Row.php
   │           │  ├─ RowCellIterator.php
   │           │  ├─ RowDimension.php
   │           │  ├─ RowIterator.php
   │           │  ├─ SheetView.php
   │           │  ├─ Table
   │           │  │  ├─ Column.php
   │           │  │  ├─ TableDxfsStyle.php
   │           │  │  └─ TableStyle.php
   │           │  ├─ Table.php
   │           │  ├─ Validations.php
   │           │  └─ Worksheet.php
   │           └─ Writer
   │              ├─ BaseWriter.php
   │              ├─ Csv.php
   │              ├─ Exception.php
   │              ├─ Html.php
   │              ├─ IWriter.php
   │              ├─ Ods
   │              │  ├─ AutoFilters.php
   │              │  ├─ Cell
   │              │  │  ├─ Comment.php
   │              │  │  └─ Style.php
   │              │  ├─ Content.php
   │              │  ├─ Formula.php
   │              │  ├─ Meta.php
   │              │  ├─ MetaInf.php
   │              │  ├─ Mimetype.php
   │              │  ├─ NamedExpressions.php
   │              │  ├─ Settings.php
   │              │  ├─ Styles.php
   │              │  ├─ Thumbnails.php
   │              │  └─ WriterPart.php
   │              ├─ Ods.php
   │              ├─ Pdf
   │              │  ├─ Dompdf.php
   │              │  ├─ Mpdf.php
   │              │  └─ Tcpdf.php
   │              ├─ Pdf.php
   │              ├─ Xls
   │              │  ├─ BIFFwriter.php
   │              │  ├─ CellDataValidation.php
   │              │  ├─ ConditionalHelper.php
   │              │  ├─ ErrorCode.php
   │              │  ├─ Escher.php
   │              │  ├─ Font.php
   │              │  ├─ Parser.php
   │              │  ├─ Style
   │              │  │  ├─ CellAlignment.php
   │              │  │  ├─ CellBorder.php
   │              │  │  └─ CellFill.php
   │              │  ├─ Workbook.php
   │              │  ├─ Worksheet.php
   │              │  └─ Xf.php
   │              ├─ Xls.php
   │              ├─ Xlsx
   │              │  ├─ AutoFilter.php
   │              │  ├─ Chart.php
   │              │  ├─ Comments.php
   │              │  ├─ ContentTypes.php
   │              │  ├─ DefinedNames.php
   │              │  ├─ DocProps.php
   │              │  ├─ Drawing.php
   │              │  ├─ FunctionPrefix.php
   │              │  ├─ Metadata.php
   │              │  ├─ Rels.php
   │              │  ├─ RelsRibbon.php
   │              │  ├─ RelsVBA.php
   │              │  ├─ StringTable.php
   │              │  ├─ Style.php
   │              │  ├─ Table.php
   │              │  ├─ Theme.php
   │              │  ├─ Workbook.php
   │              │  ├─ Worksheet.php
   │              │  └─ WriterPart.php
   │              ├─ Xlsx.php
   │              ├─ ZipStream0.php
   │              ├─ ZipStream2.php
   │              └─ ZipStream3.php
   ├─ psr
   │  ├─ http-client
   │  │  ├─ CHANGELOG.md
   │  │  ├─ composer.json
   │  │  ├─ LICENSE
   │  │  ├─ README.md
   │  │  └─ src
   │  │     ├─ ClientExceptionInterface.php
   │  │     ├─ ClientInterface.php
   │  │     ├─ NetworkExceptionInterface.php
   │  │     └─ RequestExceptionInterface.php
   │  ├─ http-factory
   │  │  ├─ composer.json
   │  │  ├─ LICENSE
   │  │  ├─ README.md
   │  │  └─ src
   │  │     ├─ RequestFactoryInterface.php
   │  │     ├─ ResponseFactoryInterface.php
   │  │     ├─ ServerRequestFactoryInterface.php
   │  │     ├─ StreamFactoryInterface.php
   │  │     ├─ UploadedFileFactoryInterface.php
   │  │     └─ UriFactoryInterface.php
   │  ├─ http-message
   │  │  ├─ CHANGELOG.md
   │  │  ├─ composer.json
   │  │  ├─ docs
   │  │  │  ├─ PSR7-Interfaces.md
   │  │  │  └─ PSR7-Usage.md
   │  │  ├─ LICENSE
   │  │  ├─ README.md
   │  │  └─ src
   │  │     ├─ MessageInterface.php
   │  │     ├─ RequestInterface.php
   │  │     ├─ ResponseInterface.php
   │  │     ├─ ServerRequestInterface.php
   │  │     ├─ StreamInterface.php
   │  │     ├─ UploadedFileInterface.php
   │  │     └─ UriInterface.php
   │  └─ simple-cache
   │     ├─ .editorconfig
   │     ├─ composer.json
   │     ├─ LICENSE.md
   │     ├─ README.md
   │     └─ src
   │        ├─ CacheException.php
   │        ├─ CacheInterface.php
   │        └─ InvalidArgumentException.php
   └─ tecnickcom
      └─ tcpdf
         ├─ CHANGELOG.TXT
         ├─ composer.json
         ├─ config
         │  └─ tcpdf_config.php
         ├─ fonts
         │  ├─ aealarabiya.ctg.z
         │  ├─ aealarabiya.php
         │  ├─ aealarabiya.z
         │  ├─ aefurat.ctg.z
         │  ├─ aefurat.php
         │  ├─ aefurat.z
         │  ├─ ae_fonts_2.0
         │  │  ├─ ChangeLog
         │  │  ├─ COPYING
         │  │  └─ README
         │  ├─ cid0cs.php
         │  ├─ cid0ct.php
         │  ├─ cid0jp.php
         │  ├─ cid0kr.php
         │  ├─ courier.php
         │  ├─ courierb.php
         │  ├─ courierbi.php
         │  ├─ courieri.php
         │  ├─ dejavu-fonts-ttf-2.33
         │  │  ├─ AUTHORS
         │  │  ├─ BUGS
         │  │  ├─ langcover.txt
         │  │  ├─ LICENSE
         │  │  ├─ NEWS
         │  │  ├─ README
         │  │  └─ unicover.txt
         │  ├─ dejavu-fonts-ttf-2.34
         │  │  ├─ AUTHORS
         │  │  ├─ BUGS
         │  │  ├─ langcover.txt
         │  │  ├─ LICENSE
         │  │  ├─ NEWS
         │  │  ├─ README
         │  │  └─ unicover.txt
         │  ├─ dejavusans.ctg.z
         │  ├─ dejavusans.php
         │  ├─ dejavusans.z
         │  ├─ dejavusansb.ctg.z
         │  ├─ dejavusansb.php
         │  ├─ dejavusansb.z
         │  ├─ dejavusansbi.ctg.z
         │  ├─ dejavusansbi.php
         │  ├─ dejavusansbi.z
         │  ├─ dejavusanscondensed.ctg.z
         │  ├─ dejavusanscondensed.php
         │  ├─ dejavusanscondensed.z
         │  ├─ dejavusanscondensedb.ctg.z
         │  ├─ dejavusanscondensedb.php
         │  ├─ dejavusanscondensedb.z
         │  ├─ dejavusanscondensedbi.ctg.z
         │  ├─ dejavusanscondensedbi.php
         │  ├─ dejavusanscondensedbi.z
         │  ├─ dejavusanscondensedi.ctg.z
         │  ├─ dejavusanscondensedi.php
         │  ├─ dejavusanscondensedi.z
         │  ├─ dejavusansextralight.ctg.z
         │  ├─ dejavusansextralight.php
         │  ├─ dejavusansextralight.z
         │  ├─ dejavusansi.ctg.z
         │  ├─ dejavusansi.php
         │  ├─ dejavusansi.z
         │  ├─ dejavusansmono.ctg.z
         │  ├─ dejavusansmono.php
         │  ├─ dejavusansmono.z
         │  ├─ dejavusansmonob.ctg.z
         │  ├─ dejavusansmonob.php
         │  ├─ dejavusansmonob.z
         │  ├─ dejavusansmonobi.ctg.z
         │  ├─ dejavusansmonobi.php
         │  ├─ dejavusansmonobi.z
         │  ├─ dejavusansmonoi.ctg.z
         │  ├─ dejavusansmonoi.php
         │  ├─ dejavusansmonoi.z
         │  ├─ dejavuserif.ctg.z
         │  ├─ dejavuserif.php
         │  ├─ dejavuserif.z
         │  ├─ dejavuserifb.ctg.z
         │  ├─ dejavuserifb.php
         │  ├─ dejavuserifb.z
         │  ├─ dejavuserifbi.ctg.z
         │  ├─ dejavuserifbi.php
         │  ├─ dejavuserifbi.z
         │  ├─ dejavuserifcondensed.ctg.z
         │  ├─ dejavuserifcondensed.php
         │  ├─ dejavuserifcondensed.z
         │  ├─ dejavuserifcondensedb.ctg.z
         │  ├─ dejavuserifcondensedb.php
         │  ├─ dejavuserifcondensedb.z
         │  ├─ dejavuserifcondensedbi.ctg.z
         │  ├─ dejavuserifcondensedbi.php
         │  ├─ dejavuserifcondensedbi.z
         │  ├─ dejavuserifcondensedi.ctg.z
         │  ├─ dejavuserifcondensedi.php
         │  ├─ dejavuserifcondensedi.z
         │  ├─ dejavuserifi.ctg.z
         │  ├─ dejavuserifi.php
         │  ├─ dejavuserifi.z
         │  ├─ freefont-20100919
         │  │  ├─ AUTHORS
         │  │  ├─ ChangeLog
         │  │  ├─ COPYING
         │  │  ├─ CREDITS
         │  │  ├─ INSTALL
         │  │  └─ README
         │  ├─ freefont-20120503
         │  │  ├─ AUTHORS
         │  │  ├─ ChangeLog
         │  │  ├─ COPYING
         │  │  ├─ CREDITS
         │  │  ├─ INSTALL
         │  │  ├─ README
         │  │  ├─ TROUBLESHOOTING
         │  │  └─ USAGE
         │  ├─ freemono.ctg.z
         │  ├─ freemono.php
         │  ├─ freemono.z
         │  ├─ freemonob.ctg.z
         │  ├─ freemonob.php
         │  ├─ freemonob.z
         │  ├─ freemonobi.ctg.z
         │  ├─ freemonobi.php
         │  ├─ freemonobi.z
         │  ├─ freemonoi.ctg.z
         │  ├─ freemonoi.php
         │  ├─ freemonoi.z
         │  ├─ freesans.ctg.z
         │  ├─ freesans.php
         │  ├─ freesans.z
         │  ├─ freesansb.ctg.z
         │  ├─ freesansb.php
         │  ├─ freesansb.z
         │  ├─ freesansbi.ctg.z
         │  ├─ freesansbi.php
         │  ├─ freesansbi.z
         │  ├─ freesansi.ctg.z
         │  ├─ freesansi.php
         │  ├─ freesansi.z
         │  ├─ freeserif.ctg.z
         │  ├─ freeserif.php
         │  ├─ freeserif.z
         │  ├─ freeserifb.ctg.z
         │  ├─ freeserifb.php
         │  ├─ freeserifb.z
         │  ├─ freeserifbi.ctg.z
         │  ├─ freeserifbi.php
         │  ├─ freeserifbi.z
         │  ├─ freeserifi.ctg.z
         │  ├─ freeserifi.php
         │  ├─ freeserifi.z
         │  ├─ helvetica.php
         │  ├─ helveticab.php
         │  ├─ helveticabi.php
         │  ├─ helveticai.php
         │  ├─ hysmyeongjostdmedium.php
         │  ├─ kozgopromedium.php
         │  ├─ kozminproregular.php
         │  ├─ msungstdlight.php
         │  ├─ pdfacourier.php
         │  ├─ pdfacourier.z
         │  ├─ pdfacourierb.php
         │  ├─ pdfacourierb.z
         │  ├─ pdfacourierbi.php
         │  ├─ pdfacourierbi.z
         │  ├─ pdfacourieri.php
         │  ├─ pdfacourieri.z
         │  ├─ pdfahelvetica.php
         │  ├─ pdfahelvetica.z
         │  ├─ pdfahelveticab.php
         │  ├─ pdfahelveticab.z
         │  ├─ pdfahelveticabi.php
         │  ├─ pdfahelveticabi.z
         │  ├─ pdfahelveticai.php
         │  ├─ pdfahelveticai.z
         │  ├─ pdfasymbol.php
         │  ├─ pdfasymbol.z
         │  ├─ pdfatimes.php
         │  ├─ pdfatimes.z
         │  ├─ pdfatimesb.php
         │  ├─ pdfatimesb.z
         │  ├─ pdfatimesbi.php
         │  ├─ pdfatimesbi.z
         │  ├─ pdfatimesi.php
         │  ├─ pdfatimesi.z
         │  ├─ pdfazapfdingbats.php
         │  ├─ pdfazapfdingbats.z
         │  ├─ stsongstdlight.php
         │  ├─ symbol.php
         │  ├─ times.php
         │  ├─ timesb.php
         │  ├─ timesbi.php
         │  ├─ timesi.php
         │  ├─ uni2cid_ac15.php
         │  ├─ uni2cid_ag15.php
         │  ├─ uni2cid_aj16.php
         │  ├─ uni2cid_ak12.php
         │  └─ zapfdingbats.php
         ├─ include
         │  ├─ barcodes
         │  │  ├─ datamatrix.php
         │  │  ├─ pdf417.php
         │  │  └─ qrcode.php
         │  ├─ sRGB.icc
         │  ├─ tcpdf_colors.php
         │  ├─ tcpdf_filters.php
         │  ├─ tcpdf_fonts.php
         │  ├─ tcpdf_font_data.php
         │  ├─ tcpdf_images.php
         │  └─ tcpdf_static.php
         ├─ LICENSE.TXT
         ├─ README.md
         ├─ tcpdf.php
         ├─ tcpdf_autoconfig.php
         ├─ tcpdf_barcodes_1d.php
         ├─ tcpdf_barcodes_2d.php
         ├─ tools
         │  ├─ .htaccess
         │  ├─ convert_fonts_examples.txt
         │  └─ tcpdf_addfont.php
         └─ VERSION

```