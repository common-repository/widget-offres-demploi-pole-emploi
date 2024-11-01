<?php

/**
 * Plugin Name:       Widget Offres
 * Plugin URI:        https://francetravail.io
 * Description:       Ce widget permet de bénéficier d’une interface préconstruite pour requêter et afficher sur une carte les offres d'emploi issues de l’API Offres d’emploi.
 * Version:           0.2.40
 * Author:            France Travail
 * Author URI:        https://francetravail.io
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

register_setting('pe', 'pe_params', ['default' => '{"estPluginWordpress":true,"rechercheAuto":false,"zoomInitial":5,"positionInitiale":[2.09,46.505],"technicalParameters":{"range":{"value":"0-49","show":false,"order":1},"sort":{"value":1,"show":true,"order":3}},"criterias":{"domaine":{"value":null,"show":false,"order":1},"codeROME":{"value":null,"show":false,"order":1},"appellation":{"value":null,"show":false,"order":1},"theme":{"value":null,"show":false,"order":1},"secteurActivite":{"value":null,"show":false,"order":1},"experience":{"value":null,"show":false,"order":1},"typeContrat":{"value":null,"show":true,"order":2},"natureContrat":{"value":null,"show":false,"order":1},"qualification":{"value":null,"show":false,"order":1},"tempsPlein":{"value":null,"show":true,"order":4},"commune":{"value":null,"show":true,"order":1},"distance":{"value":null,"show":false,"order":1,"min":0,"max":100},"departement":{"value":null,"show":false,"order":1},"inclureLimitrophes":{"value":null,"show":false,"order":1},"region":{"value":null,"show":false,"order":1},"paysContinent":{"value":null,"show":false,"order":1},"niveauFormation":{"value":null,"show":false,"order":1},"permis":{"value":null,"show":false,"order":1},"motsCles":{"value":null,"show":true,"order":0},"salaireMin":{"value":null,"show":false,"order":1,"min":0,"max":100},"periodeSalaire":{"value":null,"show":false,"order":1},"accesTravailleurHandicape":{"value":null,"show":false,"order":1},"publieeDepuis":{"value":null,"show":false,"order":1},"offresMRS":{"value":null,"show":false,"order":1},"grandDomaine":{"value":null,"show":false,"order":1},"experienceExigence":{"value":null,"show":false,"order":1}}}']);

function peio_widget_offres_options_page()
{
	add_submenu_page(
		'tools.php',
		'Widget Offres',
		'Widget Offres',
		'manage_options',
		'widgetOffresParams',
		'peio_widget_offres_widgetOffresParams_html'
	);
}

add_action('admin_menu', 'peio_widget_offres_options_page');

function peio_widget_offres_widgetOffresParams_html()
{
	if (!current_user_can('manage_options')) {
		return;
	}

	wp_enqueue_style('bootstrap_icons', plugins_url('bootstrap-icons.css', __FILE__));
	wp_enqueue_style('PT_Sans_font', 'https://fonts.googleapis.com/css2?family=PT+Sans&display=swap');
?>

	<style>
		* {
			font-family: 'PT Sans', sans-serif;
		}

		h1,
		h2 {
			color: rgb(28, 28, 72);
		}

		#paramsWidget {
			display: flex;
			flex-wrap: wrap;
			column-gap: 20px;
		}

		.peio-criteria {
			margin-bottom: 1.2em;
			width: 300px;
			position: relative;
			padding: 10px;
			background-color: white;
		}

		.wrap input[type=text] {
			border: none;
			border-bottom: solid 1px black;
			background: transparent;
			font-style: italic;
			border-radius: 0;
			width: 90%;
		}

		.wrap input::placeholder {
			color: #8D8C8C;
		}

		.wrap label {
			font-weight: bold;
		}

		.peio-eye {
			position: absolute;
			top: 10px;
			right: 10px;
			font-size: 1.5em;
		}

		.peio-eye:hover {
			cursor: pointer;
		}

		.bloc {
			background-color: white;
			max-width: 500px;
		}

		.peio-identifiants {
			background-color: white;
			max-width: 650px;
			padding: 10px;
			display: inline-block;
			width: 100%;
		}

		.peio-identifiants-input {
			margin-bottom: 1.5em;
		}


		#peio-parametres-techniques {
			display: flex;
			flex-wrap: wrap;
			column-gap: 20px;
		}

		.switch {
			position: relative;
			display: inline-block;
			width: 40px;
			height: 20px;
			margin-top: 1.2em;
		}

		.switch input {
			opacity: 0;
			width: 0;
			height: 0;
		}

		.slider {
			position: absolute;
			cursor: pointer;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background-color: #ccc;
			-webkit-transition: .4s;
			transition: .4s;
		}

		.slider:before {
			position: absolute;
			content: "";
			height: 17px;
			width: 17px;
			left: 2px;
			bottom: 2px;
			background-color: white;
			-webkit-transition: .4s;
			transition: .4s;
		}

		input:checked+.slider {
			background-color: #2196F3;
		}

		input:focus+.slider {
			box-shadow: 0 0 1px #2196F3;
		}

		input:checked+.slider:before {
			-webkit-transform: translateX(19px);
			-ms-transform: translateX(19px);
			transform: translateX(19px);
		}

		/* Rounded sliders */
		.slider.round {
			border-radius: 34px;
		}

		.slider.round:before {
			border-radius: 50%;
		}

		#logo-peio {
			width: 300px;
			display: inline-block;
			margin-left: 2rem;

		}

		#logo-peio img {
			width: 100%;
		}

		#logo-peio2 {
			width: 300px;
			display: none;

		}

		#logo-peio2 img {
			width: 100%;
		}

		@media only screen and (max-width: 1208px) {
			#logo-peio {
				display: none;
			}
		}

		@media only screen and (max-width: 1208px) {
			#logo-peio2 {
				display: block;
			}
		}

		@media only screen and (max-width: 682px) {
			.peio-identifiants {
				width: 100%;
			}

			.peio-criteria {
				width: 100%;
			}
		}
	</style>

	<div class="wrap">

		<div id="logo-peio2">
			<img src="<?php echo plugins_url('logo.png', __FILE__); ?>">

		</div>

		<h1>Paramètres du widget</h1>


		<form action="options.php" method="post" id="paramsForm">


			<h2>Identifiants</h2>
			<p>Retrouvez les identifiants de votre application sur francetravail.io / Mon Espace</p>

			<div class="peio-identifiants">
				<div class="peio-identifiants-input">
					<label for="identifiant">Identifiant</label><br />
					<input type="text" id="identifiant" name="pe_clientid" placeholder="ex. : &quot;PAR_appli_4e56f7868465de97d73f037611c9e545c2025c2f92afe82894f2be644f2bd56d&quot;" value="<?php echo esc_attr(get_option('pe_clientid')); ?>"><br />
				</div>

				<div class="peio-identifiants-input">
					<label for="cleSecrete">Clé secrète</label><br />
					<input type="text" id="cleSecrete" name="pe_clientsecret" placeholder="ex. : &quot;20c11379e9ba27a0681e21594ad56f78eb577200f4891dea2e1adcf96b87c1a5&quot;" value="<?php echo esc_attr(get_option('pe_clientsecret')); ?>">
				</div>

			</div>
			<div id="logo-peio">
				<img src="<?php echo plugins_url('logo.png', __FILE__); ?>">

			</div>


			<h2>Paramètres techniques</h2>

			<div id="peio-parametres-techniques">
				<div class="peio-criteria">
					<label for="rechercheAuto">Recherche auto</label>
					<br />
					<label class="switch">
						<input type="checkbox" id="rechercheAuto" class="pre_params">
						<span class="slider round"></span>
					</label>
					<p>Lance la recherche automatiquement, sans afficher le formulaire</p>
				</div>
				<div class="peio-criteria">
					<label for="zoomInitial">Zoom initial</label><br />
					<input type="text" id="zoomInitial" placeholder="ex. : &quot;5&quot;" class="pre_params">
					<p>Permet de régler le niveau initial de zoom sur la carte</p>
				</div>
				<div class="peio-criteria">
					<label for="positionInitiale">Position Initiale</label><br />
					<input type="text" id="positionInitiale" placeholder="ex. : &quot;[48.8588897,2.320041]&quot;" class="pre_params">
					<p>Position initiale de la carte, sous la forme [latitude,longitude]</p>
				</div>
			</div>


			<h2>Critères de recherche</h2>

			<p style="font-size: 1.1em"><i class="bi-eye"></i> / <i class="bi-eye-slash"></i> : Afficher ou masquer le champ dans le widget</p>

			<input type="hidden" name="pe_params" id="pe_params" value="<?php echo esc_attr(get_option('pe_params')); ?>" />

			<div id="paramsWidget">

			</div>

			<?php
			settings_fields('pe');

			//do_settings_sections( 'widgetOffresParams' );
			?>


			<script>
				labels = {
					domaine: "Code du domaine professionnel",
					codeROME: "Code ROME du métier",
					appellation: "Code de l'appellation",
					theme: "Thème du métier",
					secteurActivite: "Codes NAF des secteurs d’activités",
					experience: "Niveau d’expérience demandé",
					typeContrat: "Code du type de contrat",
					natureContrat: "Code de la nature de contrat",
					qualification: "Code de la qualification",
					tempsPlein: "Temps plein",
					commune: "Code INSEE de la commune",
					distance: "Distance kilométrique du rayon de recherche",
					departement: "Code INSEE du département",
					inclureLimitrophes: "Inclure les départements limitrophes",
					region: "Code de la région",
					paysContinent: "Code du pays ou du continent",
					niveauFormation: "Niveau de formation",
					permis: "Code du permis",
					motsCles: "Recherche par mot clé",
					salaireMin: "Salaire minimum",
					periodeSalaire: "Période pour le calcul du salaire minimum",
					accesTravailleurHandicape: "Travailleur handicapé",
					publieeDepuis: "Publiée depuis",
					offresMRS: "Offres MRS",
					grandDomaine: "Code du grand domaine de l'offre",
					experienceExigence: "Expérience exigée"
				};

				placeholders = {
					domaine: "A11",
					codeROME: "A1407",
					appellation: "10301",
					theme: "16",
					secteurActivite: "08,11",
					experience: "1",
					typeContrat: "CDI",
					natureContrat: "E1",
					qualification: "9",
					tempsPlein: "false",
					commune: "62099",
					distance: "10",
					departement: "62",
					inclureLimitrophes: "true",
					region: "32",
					paysContinent: "6I",
					niveauFormation: "AFS",
					permis: "D1E",
					motsCles: "informatique",
					salaireMin: "22",
					periodeSalaire: "H",
					accesTravailleurHandicape: "true",
					publieeDepuis: "14",
					offresMRS: "false",
					grandDomaine: "H",
					experienceExigence: "D",
				};

				explications = {
					domaine: "",
					codeROME: "",
					appellation: "La recherche sur l'appellation renvoie également les résultats approchants (métiers proches au sens du ROME).",
					theme: "",
					secteurActivite: "Il est possible de spécifier deux codes NAF en les séparant par une virgule dans la chaîne de caractères.",
					experience: "Valeurs possibles :<br/> 1 -> Moins d'un an d'expérience <br/>2 -> De 1 à 3 ans d'expérience <br/>3 -> Plus de 3 ans d'expérience",
					typeContrat: "Remarque : L'opérateur utilisé entre les filtres typeContrat et natureContrat est un OU, c'est-à-dire que pour la recherche \"typeContrat=CDI,CDD\" avec \"natureContrat=E1\" alors les résultats contiendront toutes les offres en type de contrat CDI ou CDD (peu importe la nature) mais également toutes les offres de nature E1 (sans prendre en compte le type de contrat).",
					natureContrat: "Remarque : Voir la remarque sur le filtre typeContrat dans le cas où les filtres typeContrat et natureContrat sont utilisés ensemble.",
					qualification: "Valeurs possibles : <br/>0 -> Non cadre <br/>9 -> Cadre",
					tempsPlein: "Valeurs possibles : <br/>false -> Temps partiel <br/>true -> Temps plein <br/>Si le paramètre n'est pas renseigné, alors toutes les offres sont remontées",
					commune: "",
					distance: "Valeur par défaut : 10 <br/>Remarque : pour obtenir seulement les offres d'une commune spécifique, alors il faut renseigner le paramètre \"distance=0\"",
					departement: "",
					inclureLimitrophes: "",
					region: "",
					paysContinent: "",
					niveauFormation: "",
					permis: "",
					motsCles: "",
					salaireMin: "Si cette donnée est renseignée, le code du type de salaire minimum est obligatoire.",
					periodeSalaire: "Si cette donnée est renseignée, le salaire minimum est obligatoire. <br/>Valeurs possibles : <br/>M -> Mensuel <br/>A -> Annuel <br/>H -> Horaire <br/>C -> Cachet",
					accesTravailleurHandicape: "Permet de rechercher des offres pour lesquelles l'employeur est handi friendly",
					publieeDepuis: "Nombre de jours maximal depuis la publication de l'offre <br/>Valeurs possibles : 1, 3, 7, 14, 31",
					offresMRS: "Permet de rechercher des offres proposant la méthode de recrutement par simulation",
					grandDomaine: "Valeurs possibles : <br/>A -> Agriculture / Pêche / Espaces verts et naturels / Soins aux animaux <br/>B -> Arts / Artisanat d’art <br/>C -> Banque / Assurance <br/>C15 -> Immobilier <br/>D -> Commerce / Vente <br/>E -> Communication / Multimédia <br/>F -> Bâtiment / Travaux Publics <br/>G -> Hôtellerie – Restauration / Tourisme / Animation <br/>H -> Industrie <br/>I -> Installation / Maintenance <br/>J -> Santé <br/>K -> Services à la personne / à la collectivité <br/>L -> Spectacle <br/>L14 -> Sport <br/>M -> Achats / Comptabilité / Gestion <br/>M13 -> Direction d’entreprise <br/>M14 -> Conseil/Etudes <br/>M15 -> Ressources Humaines <br/>M16 -> Secrétariat/Assistanat <br/>M17 -> Marketing /Stratégie commerciale <br/>M18 -> Informatique / Télécommunication <br/>N -> Transport / Logistique",
					experienceExigence: "Filtre les offres selon le niveau d'expérience  <br/>Valeurs possibles :  <br/>D -> Débutant accepté <br/>S -> Expérience souhaitée <br/>E -> Expérience exigée"
				};
			</script>

			<script>
				<?php

				$options = json_decode(get_option('pe_params'));

				?>

				options = <?php echo wp_json_encode($options); ?>

				$ = jQuery;

				$('#rechercheAuto').prop('checked', options.rechercheAuto);
				$('#zoomInitial').val(options.zoomInitial);
				$('#positionInitiale').val('[' + options.positionInitiale + ']');

				$.each(options.criterias, function(id) {
					if (this.value == null) {
						value = '';
					} else {
						value = this.value;
					}

					if (this.show) {
						eye = 'bi-eye';
					} else {
						eye = 'bi-eye-slash';
					};

					html = `
				<div class="peio-criteria">
					<i class="${eye} peio-eye" id="peio-show-${id}"></i>
					<label id="label_${id}" for="${id}">${labels[id]}</label><br/>
					<input class="value_param" type="text" id="${id}" value="${value}" placeholder="ex. : &quot;${placeholders[id]}&quot;"><br/>
					<p>${explications[id]}</p>
				</div>
				`;

					$('#paramsWidget').append(html);

				});

				$('.value_param').change(function(e) {
					id = $(this).attr('id');
					if ($(this).val() == '') {
						value = null;
					} else {
						value = $(this).val();
						if (value == 'true') {
							value = true
						}
						if (value == 'false') {
							value = false
						}
					}
					options.criterias[id].value = value;
				});


				$('.pre_params').change(function(e) {
					if ($(this).attr('type') == 'checkbox') {
						options[$(this).attr('id')] = $(this).is(':checked');
					} else {
						if ($(this).attr('id') == 'zoomInitial') {
							options[$(this).attr('id')] = parseInt($(this).val());
						} else {
							regex = /\[(.*?),(.*?)]/;
							match = $(this).val().match(regex);
							if (match) {
								value = [parseFloat(match[1]), parseFloat(match[2])];
								options[$(this).attr('id')] = value;
							}

						}

					}

				});

				$('.peio-eye').click(function(e) {
					clickedEye = $(e.currentTarget);

					eyeId = clickedEye.attr('id').substring(10);

					if (clickedEye.hasClass('bi-eye')) {
						clickedEye.removeClass('bi-eye');
						clickedEye.addClass('bi-eye-slash');
						eyeStatus = false;
					} else {
						clickedEye.removeClass('bi-eye-slash');
						clickedEye.addClass('bi-eye');
						eyeStatus = true;
					}

					options.criterias[eyeId].show = eyeStatus;
				});


				$('#paramsForm').on('submit', function(e) {
					$('#pe_params').val(JSON.stringify(options));
				})
			</script>
			<?php
			submit_button(__('Enregistrer', 'textdomain'));
			?>
		</form>
	</div>

<?php
}




function peio_widget_offres_settings_init()
{

	register_setting('pe', 'pe_clientid');
	register_setting('pe', 'pe_clientsecret');

	add_settings_section(
		'pe_identifiants_section',
		'Identifiants API',
		'peio_widget_offres_identifiantsapi_section_callback',
		'widgetOffresParams'
	);
	add_settings_section(
		'pe_params_section',
		'Paramètres widget',
		'peio_widget_offres_identifiantsapi_section_callback',
		'widgetOffresParams'
	);


	add_settings_field(
		'pe_clientid',
		'Identifiant client',
		'peio_widget_offres_clientid_field_callback',
		'widgetOffresParams',
		'pe_identifiants_section'
	);
	add_settings_field(
		'pe_clientsecret',
		'Clé secrète',
		'peio_widget_offres_clientsecret_field_callback',
		'widgetOffresParams',
		'pe_identifiants_section'
	);

	add_settings_field(
		'pe_params',
		'Paramètres widget',
		'peio_widget_offres_params_field_callback',
		'widgetOffresParams',
		'pe_params_section'
	);
}

add_action('admin_init', 'peio_widget_offres_settings_init');



function peio_widget_offres_identifiantsapi_section_callback()
{
}

function peio_widget_offres_clientid_field_callback()
{
	$setting = get_option('pe_clientid');
?>
	<input type="text" style="width:100%;" name="pe_clientid" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>">
<?php
}
function peio_widget_offres_clientsecret_field_callback()
{
	$setting = get_option('pe_clientsecret');
?>
	<input type="text" style="width:100%;" name="pe_clientsecret" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>">
<?php
}
function peio_widget_offres_params_field_callback()
{
	$setting = get_option('pe_params');
?>


	<input type="hidden" name="pe_params" id="pe_params" value="<?php echo wp_json_encode($options); ?>" />
<?php
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


add_shortcode('widget-offres-pe', 'peio_widget_offres_widget_offres_shortcode');
function peio_widget_offres_widget_offres_shortcode($atts = [], $content = null)
{



	$clientId = get_option('pe_clientid');
	$clientSecret = get_option('pe_clientsecret');

	$httpBody = [
		'grant_type' => 'client_credentials',
		'client_id' => $clientId,
		'client_secret' => $clientSecret,
		'scope' => 'api_offresdemploiv2 o2dsoffre application_' . $clientId
	];

	$args = array(
		'body'        => $httpBody,
		'timeout'     => '5',
		'redirection' => '5',
		'httpversion' => '1.0',
		'blocking'    => true,
		'headers'     => array(),
		'cookies'     => array(),
	);

	$response = wp_remote_post('https://entreprise.pole-emploi.fr/connexion/oauth2/access_token?realm=/partenaire', $args);

	$token = json_decode(wp_remote_retrieve_body($response))->access_token;
	$options = json_decode(get_option('pe_params'));

	wp_enqueue_script('widget_offres', 'https://francetravail.io/data/widget/pe-offres-emploi.js');

	ob_start();
?>
	<pe-offres-emploi></pe-offres-emploi>
	<script>
		var macarte = document.querySelector('pe-offres-emploi');
	</script>
	<script>
		macarte.token = "<?php echo esc_js($token); ?>"
	</script>
	<script>
		macarte.options = <?php echo wp_json_encode($options); ?>
	</script>
<?php

	return ob_get_clean();
}


function peio_widget_offres_uninstall()
{

	delete_option('pe_clientid');
	delete_option('pe_clientsecret');
	delete_option('pe_params');
}
register_uninstall_hook(__FILE__, 'peio_widget_offres_uninstall');
