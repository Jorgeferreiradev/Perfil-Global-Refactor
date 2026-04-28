# Plataforma Perfil Global V2 - FESC

Sistema de gestión académica, control de asistencia por QR y carga masiva de datos para la Fundación de Estudios Superiores Comfanorte (FESC). Construido bajo arquitectura MVC con PHP puro.

##  Requisitos del Servidor
- PHP 8.1 o superior.
- MySQL 8.0 / MariaDB.
- Extensiones PHP requeridas: `pdo_mysql`, `mbstring`, `gd` (Para generación de QR).
- Composer instalado en el servidor.

##  Instrucciones de Despliegue
1. Clonar el repositorio en la carpeta pública del servidor (Ej. `public_html` o `/var/www/html`).
2. Ejecutar `composer install` para descargar las dependencias (Bramus Router, PHPMailer, PHPSpreadsheet, Chillerlan QR).
3. Duplicar el archivo `.env.example`, renombrarlo a `.env` y configurar las credenciales.
4. Importar el archivo `database.sql` en el motor de base de datos.
5. Apuntar el Document Root del servidor web (Apache/Nginx) a la carpeta `/public` del proyecto.
6. (Opcional) Configurar un Cron Job para ejecutar `/cron/backup.php` semanalmente a las 8:00 pm.



## Roles
Admin
Monitor
Superadmin

## Seguridad
- PDO prepared statements
- password_hash
- control de acceso por roles
## Tecnologías
- PHP 8.1
- MySQL
- JavaScript
- Bootstrap




Listado de rutas de carpetas
El número de serie del volumen es 18F7-D42A
C:.
├───.github
├───app
│   ├───Controllers
│   ├───Helpers
│   ├───Middleware
│   ├───Models
│   └───Services
├───config
├───cron
├───docs
├───public
│   └───assets
│       ├───img
│       └───templates
├───resources
│   └───views
│       ├───admin
│       ├───auth
│       ├───dashboard
│       ├───events
│       ├───layouts
│       ├───perfil
│       ├───personas
│       ├───programas
│       ├───public
│       └───reports
└───vendor
    ├───bacon
    │   └───bacon-qr-code
    │       ├───.github
    │       │   └───workflows
    │       ├───src
    │       │   ├───Common
    │       │   ├───Encoder
    │       │   ├───Exception
    │       │   └───Renderer
    │       │       ├───Color
    │       │       ├───Eye
    │       │       ├───Image
    │       │       ├───Module
    │       │       │   └───EdgeIterator
    │       │       ├───Path
    │       │       └───RendererStyle
    │       └───test
    │           ├───Common
    │           ├───Encoder
    │           └───Integration
    │               └───__snapshots__
    ├───bin
    ├───bramus
    │   └───router
    │       ├───.github
    │       │   └───workflows
    │       ├───demo
    │       ├───demo-multilang
    │       ├───src
    │       │   └───Bramus
    │       │       └───Router
    │       └───tests
    ├───carbonphp
    │   └───carbon-doctrine-types
    │       └───src
    │           └───Carbon
    │               └───Doctrine
    ├───chillerlan
    │   ├───php-qrcode
    │   │   └───src
    │   │       ├───Common
    │   │       ├───Data
    │   │       ├───Decoder
    │   │       ├───Detector
    │   │       └───Output
    │   └───php-settings-container
    │       └───src
    ├───composer
    │   └───pcre
    │       └───src
    │           └───PHPStan
    ├───dasprid
    │   └───enum
    │       ├───.github
    │       │   └───workflows
    │       ├───src
    │       │   └───Exception
    │       └───test
    ├───endroid
    │   └───qr-code
    │       ├───.github
    │       │   └───workflows
    │       ├───assets
    │       ├───src
    │       │   ├───Bacon
    │       │   ├───Builder
    │       │   ├───Color
    │       │   ├───Encoding
    │       │   ├───Exception
    │       │   ├───ImageData
    │       │   ├───Label
    │       │   │   ├───Font
    │       │   │   └───Margin
    │       │   ├───Logo
    │       │   ├───Matrix
    │       │   └───Writer
    │       │       └───Result
    │       └───tests
    │           └───assets
    ├───graham-campbell
    │   └───result-type
    │       ├───.github
    │       │   └───workflows
    │       ├───src
    │       └───tests
    ├───maennchen
    │   └───zipstream-php
    │       ├───.github
    │       │   ├───ISSUE_TEMPLATE
    │       │   ├───PULL_REQUEST_TEMPLATE
    │       │   └───workflows
    │       ├───.phive
    │       ├───.phpdoc
    │       │   └───template
    │       ├───guides
    │       ├───src
    │       │   ├───Exception
    │       │   ├───Zip64
    │       │   └───Zs
    │       └───test
    │           ├───Zip64
    │           └───Zs
    ├───markbaker
    │   ├───complex
    │   │   ├───.github
    │   │   │   └───workflows
    │   │   ├───classes
    │   │   │   └───src
    │   │   └───examples
    │   └───matrix
    │       ├───.github
    │       │   └───workflows
    │       ├───classes
    │       │   └───src
    │       │       ├───Decomposition
    │       │       └───Operators
    │       └───examples
    ├───nesbot
    │   └───carbon
    │       ├───.github
    │       │   ├───ISSUE_TEMPLATE
    │       │   └───workflows
    │       ├───bin
    │       ├───lazy
    │       │   └───Carbon
    │       │       └───MessageFormatter
    │       ├───src
    │       │   └───Carbon
    │       │       ├───Cli
    │       │       ├───Exceptions
    │       │       ├───Lang
    │       │       ├───Laravel
    │       │       ├───List
    │       │       ├───MessageFormatter
    │       │       ├───PHPStan
    │       │       └───Traits
    │       └───tests
    │           ├───Carbon
    │           │   ├───Exceptions
    │           │   └───Fixtures
    │           ├───CarbonImmutable
    │           │   └───Fixtures
    │           ├───CarbonInterval
    │           │   └───Fixtures
    │           ├───CarbonPeriod
    │           │   └───Fixtures
    │           ├───CarbonPeriodImmutable
    │           ├───CarbonTimeZone
    │           │   └───Fixtures
    │           ├───Cli
    │           ├───CommonTraits
    │           ├───Doctrine
    │           ├───Factory
    │           ├───Fixtures
    │           ├───Jenssegers
    │           ├───Language
    │           ├───Laravel
    │           ├───Localization
    │           ├───PHPStan
    │           ├───PHPUnit
    │           └───Unit
    ├───phpmailer
    │   └───phpmailer
    │       ├───.github
    │       │   ├───actions
    │       │   │   └───build-docs
    │       │   ├───ISSUE_TEMPLATE
    │       │   └───workflows
    │       ├───.phan
    │       ├───docs
    │       ├───examples
    │       │   └───images
    │       ├───language
    │       ├───src
    │       └───test
    │           ├───Fixtures
    │           │   ├───FileIsAccessibleTest
    │           │   └───LocalizationTest
    │           ├───Language
    │           ├───OAuth
    │           ├───PHPMailer
    │           ├───POP3
    │           └───Security
    ├───phpoffice
    │   └───phpspreadsheet
    │       ├───.github
    │       │   └───workflows
    │       ├───bin
    │       ├───docs
    │       │   ├───assets
    │       │   ├───extra
    │       │   ├───references
    │       │   └───topics
    │       │       └───images
    │       │           ├───Behind the Mask
    │       │           ├───Looping the Loop
    │       │           └───The Dating Game
    │       ├───infra
    │       ├───samples
    │       │   ├───Autofilter
    │       │   ├───Basic
    │       │   ├───Basic1
    │       │   ├───Basic2
    │       │   ├───Basic3
    │       │   │   └───data
    │       │   │       └───continents
    │       │   ├───Basic4
    │       │   ├───Bitwise
    │       │   ├───bootstrap
    │       │   │   ├───css
    │       │   │   ├───fonts
    │       │   │   └───js
    │       │   ├───Chart
    │       │   ├───Chart33a
    │       │   ├───Chart33b
    │       │   ├───ComplexNumbers1
    │       │   ├───ComplexNumbers2
    │       │   ├───ComplexNumbers3
    │       │   ├───ConditionalFormatting
    │       │   ├───Database
    │       │   ├───DateTime
    │       │   ├───DateTime2
    │       │   ├───DefinedNames
    │       │   ├───Engineering
    │       │   ├───Financial1
    │       │   ├───Financial2
    │       │   ├───Financial3
    │       │   ├───HexEtcConversions
    │       │   ├───images
    │       │   ├───LookupRef
    │       │   ├───Pdf
    │       │   ├───Reader
    │       │   │   └───sampleData
    │       │   ├───Reader2
    │       │   │   └───sampleData
    │       │   ├───Reading_workbook_data
    │       │   │   └───sampleData
    │       │   ├───Table
    │       │   ├───templates
    │       │   └───Wizards
    │       │       └───NumberFormat
    │       ├───src
    │       │   └───PhpSpreadsheet
    │       │       ├───Calculation
    │       │       │   ├───Database
    │       │       │   ├───DateTimeExcel
    │       │       │   ├───Engine
    │       │       │   │   └───Operands
    │       │       │   ├───Engineering
    │       │       │   ├───Financial
    │       │       │   │   ├───CashFlow
    │       │       │   │   │   ├───Constant
    │       │       │   │   │   │   └───Periodic
    │       │       │   │   │   └───Variable
    │       │       │   │   └───Securities
    │       │       │   ├───Information
    │       │       │   ├───Internal
    │       │       │   ├───locale
    │       │       │   │   ├───bg
    │       │       │   │   ├───cs
    │       │       │   │   ├───da
    │       │       │   │   ├───de
    │       │       │   │   ├───en
    │       │       │   │   │   └───uk
    │       │       │   │   ├───es
    │       │       │   │   ├───fi
    │       │       │   │   ├───fr
    │       │       │   │   ├───hu
    │       │       │   │   ├───it
    │       │       │   │   ├───nb
    │       │       │   │   ├───nl
    │       │       │   │   ├───pl
    │       │       │   │   ├───pt
    │       │       │   │   │   └───br
    │       │       │   │   ├───ru
    │       │       │   │   ├───sv
    │       │       │   │   └───tr
    │       │       │   ├───Logical
    │       │       │   ├───LookupRef
    │       │       │   ├───MathTrig
    │       │       │   │   └───Trig
    │       │       │   ├───Statistical
    │       │       │   │   ├───Averages
    │       │       │   │   └───Distributions
    │       │       │   ├───TextData
    │       │       │   ├───Token
    │       │       │   └───Web
    │       │       ├───Cell
    │       │       ├───Chart
    │       │       │   └───Renderer
    │       │       ├───Collection
    │       │       │   └───Memory
    │       │       ├───Document
    │       │       ├───Helper
    │       │       ├───Reader
    │       │       │   ├───Csv
    │       │       │   ├───Gnumeric
    │       │       │   ├───Ods
    │       │       │   ├───Security
    │       │       │   ├───Xls
    │       │       │   │   ├───Color
    │       │       │   │   └───Style
    │       │       │   ├───Xlsx
    │       │       │   └───Xml
    │       │       │       └───Style
    │       │       ├───RichText
    │       │       ├───Shared
    │       │       │   ├───Escher
    │       │       │   │   ├───DgContainer
    │       │       │   │   │   └───SpgrContainer
    │       │       │   │   └───DggContainer
    │       │       │   │       └───BstoreContainer
    │       │       │   │           └───BSE
    │       │       │   ├───OLE
    │       │       │   │   └───PPS
    │       │       │   └───Trend
    │       │       ├───Style
    │       │       │   ├───ConditionalFormatting
    │       │       │   │   └───Wizard
    │       │       │   └───NumberFormat
    │       │       │       └───Wizard
    │       │       ├───Worksheet
    │       │       │   ├───AutoFilter
    │       │       │   │   └───Column
    │       │       │   ├───Drawing
    │       │       │   └───Table
    │       │       └───Writer
    │       │           ├───Ods
    │       │           │   └───Cell
    │       │           ├───Pdf
    │       │           ├───Xls
    │       │           │   └───Style
    │       │           └───Xlsx
    │       └───tests
    │           ├───data
    │           │   ├───Calculation
    │           │   │   ├───DateTime
    │           │   │   ├───DefinedNames
    │           │   │   ├───Engineering
    │           │   │   ├───Financial
    │           │   │   ├───Functions
    │           │   │   ├───Information
    │           │   │   ├───Logical
    │           │   │   ├───LookupRef
    │           │   │   ├───MathTrig
    │           │   │   ├───Statistical
    │           │   │   ├───TextData
    │           │   │   └───Web
    │           │   ├───Cell
    │           │   ├───Features
    │           │   │   └───AutoFilter
    │           │   │       └───Xlsx
    │           │   ├───Functional
    │           │   │   └───TypeAttributePreservation
    │           │   ├───Reader
    │           │   │   ├───CSV
    │           │   │   ├───Gnumeric
    │           │   │   ├───HTML
    │           │   │   ├───Ods
    │           │   │   ├───Slk
    │           │   │   ├───XLS
    │           │   │   ├───XLSX
    │           │   │   └───Xml
    │           │   ├───Shared
    │           │   │   ├───Date
    │           │   │   ├───FakeFonts
    │           │   │   │   ├───Default
    │           │   │   │   ├───Mac
    │           │   │   │   └───Recurse
    │           │   │   │       └───TrueType
    │           │   │   ├───OLERead
    │           │   │   └───Trend
    │           │   ├───Style
    │           │   │   ├───Color
    │           │   │   └───ConditionalFormatting
    │           │   ├───Worksheet
    │           │   │   └───Table
    │           │   └───Writer
    │           │       ├───Ods
    │           │       └───XLSX
    │           └───PhpSpreadsheetTests
    │               ├───Calculation
    │               │   ├───Engine
    │               │   └───Functions
    │               │       ├───Database
    │               │       ├───DateTime
    │               │       ├───Engineering
    │               │       ├───Financial
    │               │       ├───Information
    │               │       ├───Logical
    │               │       ├───LookupRef
    │               │       ├───MathTrig
    │               │       ├───Statistical
    │               │       ├───TextData
    │               │       └───Web
    │               ├───Cell
    │               ├───Chart
    │               ├───Collection
    │               ├───Custom
    │               ├───Document
    │               ├───Features
    │               │   └───AutoFilter
    │               │       └───Xlsx
    │               ├───Functional
    │               ├───Helper
    │               ├───Reader
    │               │   ├───Csv
    │               │   ├───Gnumeric
    │               │   ├───Html
    │               │   ├───Ods
    │               │   ├───Security
    │               │   ├───Slk
    │               │   ├───Utility
    │               │   ├───Xls
    │               │   ├───Xlsx
    │               │   └───Xml
    │               ├───Shared
    │               │   └───Trend
    │               ├───Style
    │               │   ├───ConditionalFormatting
    │               │   │   └───Wizard
    │               │   └───NumberFormat
    │               │       └───Wizard
    │               ├───Worksheet
    │               │   ├───AutoFilter
    │               │   └───Table
    │               └───Writer
    │                   ├───Csv
    │                   ├───Dompdf
    │                   ├───Html
    │                   ├───Mpdf
    │                   ├───Ods
    │                   ├───Tcpdf
    │                   ├───Xls
    │                   └───Xlsx
    ├───phpoption
    │   └───phpoption
    │       ├───.github
    │       │   └───workflows
    │       ├───src
    │       │   └───PhpOption
    │       ├───tests
    │       │   └───PhpOption
    │       │       └───Tests
    │       └───vendor-bin
    │           └───phpstan
    ├───psr
    │   ├───clock
    │   │   └───src
    │   ├───http-client
    │   │   └───src
    │   ├───http-factory
    │   │   └───src
    │   ├───http-message
    │   │   ├───docs
    │   │   └───src
    │   └───simple-cache
    │       └───src
    ├───symfony
    │   ├───clock
    │   │   ├───.github
    │   │   │   └───workflows
    │   │   ├───Resources
    │   │   ├───Test
    │   │   └───Tests
    │   ├───deprecation-contracts
    │   │   └───.github
    │   │       └───workflows
    │   ├───polyfill-ctype
    │   ├───polyfill-mbstring
    │   │   └───Resources
    │   │       └───unidata
    │   ├───polyfill-php80
    │   │   └───Resources
    │   │       └───stubs
    │   ├───polyfill-php83
    │   │   └───Resources
    │   │       └───stubs
    │   ├───translation
    │   │   ├───.github
    │   │   │   └───workflows
    │   │   ├───Catalogue
    │   │   ├───Command
    │   │   ├───DataCollector
    │   │   ├───DependencyInjection
    │   │   ├───Dumper
    │   │   ├───Exception
    │   │   ├───Extractor
    │   │   │   └───Visitor
    │   │   ├───Formatter
    │   │   ├───Loader
    │   │   ├───Provider
    │   │   ├───Reader
    │   │   ├───Resources
    │   │   │   ├───bin
    │   │   │   ├───data
    │   │   │   └───schemas
    │   │   ├───Test
    │   │   ├───Tests
    │   │   │   ├───Catalogue
    │   │   │   ├───Command
    │   │   │   ├───DataCollector
    │   │   │   ├───DependencyInjection
    │   │   │   │   └───Fixtures
    │   │   │   ├───Dumper
    │   │   │   ├───Exception
    │   │   │   ├───Extractor
    │   │   │   ├───Fixtures
    │   │   │   │   ├───extractor
    │   │   │   │   ├───extractor-7.3
    │   │   │   │   ├───extractor-ast
    │   │   │   │   └───resourcebundle
    │   │   │   │       ├───corrupted
    │   │   │   │       ├───dat
    │   │   │   │       └───res
    │   │   │   ├───Formatter
    │   │   │   ├───Loader
    │   │   │   ├───Provider
    │   │   │   ├───Util
    │   │   │   └───Writer
    │   │   ├───Util
    │   │   └───Writer
    │   └───translation-contracts
    │       ├───.github
    │       │   └───workflows
    │       └───Test
    ├───tecnickcom
    │   └───tcpdf
    │       ├───.github
    │       │   └───workflows
    │       ├───config
    │       ├───examples
    │       │   ├───barcodes
    │       │   ├───config
    │       │   ├───data
    │       │   │   └───cert
    │       │   ├───images
    │       │   └───lang
    │       ├───fonts
    │       │   ├───ae_fonts_2.0
    │       │   ├───dejavu-fonts-ttf-2.33
    │       │   ├───dejavu-fonts-ttf-2.34
    │       │   ├───freefont-20100919
    │       │   └───freefont-20120503
    │       ├───include
    │       │   └───barcodes
    │       ├───scripts
    │       ├───tests
    │       │   └───src
    │       └───tools
    └───vlucas
        └───phpdotenv
            ├───.github
            │   └───workflows
            ├───src
            │   ├───Exception
            │   ├───Loader
            │   ├───Parser
            │   ├───Repository
            │   │   └───Adapter
            │   ├───Store
            │   │   └───File
            │   └───Util
            ├───tests
            │   ├───Dotenv
            │   │   ├───Loader
            │   │   ├───Parser
            │   │   ├───Repository
            │   │   │   └───Adapter
            │   │   └───Store
            │   └───fixtures
            │       └───env
            └───vendor-bin
                └───phpstan