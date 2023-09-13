<?php
/**
 * @package Annuaire
 * @version 1.0.0
 */
/*
 * Plugin Name: Annuaire
 * Plugin URI: https://quentinjoanon.vercel.app/
 * Description: Annuaire pour créer des fiches utilisateurs
 * Version: 1.0.0
 * Author: Quentin Joanon
 * Author URI: https://quentinjoanon.vercel.app/
 */

function mon_plugin_menu() {
    add_menu_page('Annuaire', 'Annuaire', 'manage_options', 'afficher-clients', 'afficher_liste_clients');
    add_submenu_page('afficher-clients', 'Ajouter un client', 'Ajouter un client', 'manage_options', 'ajouter-client', 'afficher_page_fiches_clients');
}

function afficher_page_fiches_clients() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_client'])) {
        // Récupérer les données du formulaire
        $raison_sociale = sanitize_text_field($_POST['raison_sociale']);
        $dirigeant = sanitize_text_field($_POST['dirigeant']);
        $adresse = sanitize_text_field($_POST['adresse']);
        $phone = sanitize_text_field($_POST['phone']);
        $email = sanitize_text_field($_POST['email']);
        $website = sanitize_text_field($_POST['website']);
        $content = sanitize_text_field($_POST['content']);
        $categories_existantes = $_POST['categories_existantes']; // Récupérer les catégories existantes choisies
        $nouvelles_categories = sanitize_text_field($_POST['nouvelles_categories']); // Récupérer les nouvelles catégories ajoutées

        // Créer un tableau de données pour wp_insert_post
        $nouveau_client = array(
            'post_title' => $raison_sociale,
            'post_type' => 'fiche_client', // Remplacez 'fiche_client' par le nom de votre type de publication personnalisé.
            'post_status' => 'publish'
        );

        // Insérer le client dans la base de données
        $client_id = wp_insert_post($nouveau_client);

        // Enregistrer les autres données dans les champs personnalisés
        update_post_meta($client_id, 'raison_sociale', $raison_sociale);
        update_post_meta($client_id, 'dirigeant', $dirigeant);
        update_post_meta($client_id, 'adresse', $adresse);
        update_post_meta($client_id, 'phone', $phone);
        update_post_meta($client_id, 'email', $email);
        update_post_meta($client_id, 'website', $website);
        update_post_meta($client_id, 'content', $content);

        // Associer le client aux catégories existantes
        if (!empty($categories_existantes)) {
            $categories_ids = array();

            foreach ($categories_existantes as $categorie) {
                $term_id = (int) $categorie;
                $categories_ids[] = $term_id;
            }

            // Associez les catégories au client
            wp_set_post_terms($client_id, $categories_ids, 'categorie_client');
        }

        // Associer le client aux nouvelles catégories
        if (!empty($nouvelles_categories)) {
            $nouvelles_categories = explode(',', $nouvelles_categories);

            foreach ($nouvelles_categories as $categorie) {
                $categorie = trim($categorie);

                // Vérifier si la catégorie existe déjà
                $term_exists = term_exists($categorie, 'categorie_client');

                if ($term_exists) {
                    $term_id = $term_exists['term_id'];
                } else {
                    // Si la catégorie n'existe pas, créez-la
                    $new_term = wp_insert_term($categorie, 'categorie_client');
                    if (!is_wp_error($new_term)) {
                        $term_id = $new_term['term_id'];
                    }
                }

                if (isset($term_id)) {
                    $categories_ids[] = $term_id;
                }
            }

            // Associez les nouvelles catégories au client
            wp_set_post_terms($client_id, $categories_ids, 'categorie_client');
        }

        // Afficher un message de succès ou de confirmation
        echo '<div class="updated"><p>Le client a été ajouté avec succès.</p></div>';
    } else {

    // Récupérer toutes les catégories existantes
    $categories_existantes = get_terms(array(
        'taxonomy' => 'categorie_client',
        'hide_empty' => false,
    ));

    ?>
    <div class="wrap">
        <h2>Ajouter un client</h2>
        <form method="post">
            <label for="raison_sociale">Raison sociale :</label>
            <input type="text" name="raison_sociale" id="raison_sociale" required>
            <br>
            <label for="dirigeant">Dirigeant :</label>
            <input type="text" name="dirigeant" id="dirigeant" required>
            <br>
            <label for="adresse">Adresse :</label>
            <input type="text" name="adresse" id="adresse" required>
            <br>
            <label for="phone">Tel :</label>
            <input type="tel" name="phone" id="phone" pattern="[0-9]{10}" required>
            <br>
            <label for="email">Email :</label>
            <input type="email" name="email" id="email" required>
            <br>
            <label for="website">Site web :</label>
            <input type="url" name="website" id="website" placeholder="https://example.com" pattern="https://.*" required>
            <br>
            <label for="content">Activité principale :</label>
            <textarea name="content" id="content" required></textarea>
            <br>
            
            <!-- Champ de sélection pour les catégories existantes -->
            <label for="categories_existantes">Catégories existantes :</label>
            <select name="categories_existantes[]" id="categories_existantes" multiple>
                <?php
                foreach ($categories_existantes as $categorie) {
                    echo '<option value="' . $categorie->term_id . '">' . $categorie->name . '</option>';
                }
                ?>
            </select>
            <br>

            <!-- Champ de texte pour ajouter de nouvelles catégories -->
            <label for="nouvelles_categories">Nouvelles catégories (séparez par des virgules) :</label>
            <input type="text" name="nouvelles_categories" id="nouvelles_categories">

            <!-- Ajoutez d'autres champs de formulaire pour les autres informations du client ici. -->
            <br>
            <input type="submit" name="ajouter_client" value="Ajouter Client">
        </form>
    </div>
    <?php
    }

}


function afficher_liste_clients() {
    $args = array(
        'post_type' => 'fiche_client',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    );

    $clients = new WP_Query($args);

    if ($clients->have_posts()) {
        echo '<h2>Liste des clients</h2>';
        echo '<ul>';

        while ($clients->have_posts()) {
            $clients->the_post();
            echo '<li>';
            echo '<strong>' . get_the_title() . '</strong>';
            echo '<br><strong>Dirigeant :</strong> ' . get_post_meta(get_the_ID(), 'dirigeant', true) . '';
            echo '<br><strong>Adresse :</strong> ' . get_post_meta(get_the_ID(), 'adresse', true) . '';
            echo '<br><strong>Tel :</strong> ' . get_post_meta(get_the_ID(), 'phone', true) . '';
            echo '<br><strong>Email :</strong> ' . get_post_meta(get_the_ID(), 'email', true) . '';
            echo '<br><strong>Site web :</strong> ' . get_post_meta(get_the_ID(), 'website', true) . '';
            echo '<br><strong>Activité principale :</strong> ' . get_post_meta(get_the_ID(), 'content', true) . '';
            
            // Afficher les catégories associées
            $categories = get_the_terms(get_the_ID(), 'categorie_client');
            if ($categories) {
                echo '<br><strong>Catégories :</strong> ';
                echo '<ul>';
                foreach ($categories as $category) {
                    echo '<li>' . $category->name . '</li>';
                }
                echo '</ul>';
            }
                        
            // Ajouter les boutons d'édition et de suppression
            echo '<br><a href="#" class="button button-danger supprimer-client" data-client-id="' . get_the_ID() . '">Supprimer</a>';
            
            // Ajouter l'appel à la fonction supprimer_client()
            echo '<input type="hidden" id="id_client" value="' . get_the_ID() . '">';
            
            echo '</li>';
        }

        echo '</ul>';
    } else {
        echo '<p>Aucun client trouvé.</p>';
    }

        // Ajouter le code JavaScript ici
        ?>
        <script>
            jQuery(document).ready(function($) {
                $(".supprimer-client").on("click", function(e) {
                    e.preventDefault();
                    var clientId = $(this).data("client-id");
                    if (confirm("Voulez-vous vraiment supprimer ce client ?")) {
                        $.ajax({
                            type: "POST",
                            url: ajaxurl, // Utilisez cette variable fournie par WordPress pour l'URL Ajax
                            data: {
                                action: "supprimer_client",
                                client_id: clientId,
                            },
                            success: function(response) {
                                if (response === "success") {
                                    // Rafraîchissez la page ou effectuez toute autre action que vous souhaitez après la suppression réussie.
                                    location.reload();
                                } else {
                                    alert("Erreur lors de la suppression du client.");
                                }
                            },
                        });
                    }
                });
            });
        </script>
        <?php
    
}


function enregistrer_taxonomy_client() {
    $labels = array(
        'name' => 'Catégories Clients',
        'singular_name' => 'Catégorie Client',
        'search_items' => 'Rechercher des catégories clients',
        'all_items' => 'Toutes les catégories clients',
        'edit_item' => 'Modifier la catégorie client',
        'update_item' => 'Mettre à jour la catégorie client',
        'add_new_item' => 'Ajouter une nouvelle catégorie client',
        'new_item_name' => 'Nouveau nom de la catégorie client',
        'menu_name' => 'Catégories Clients',
    );

    $args = array(
        'hierarchical' => true, // Si vous voulez une hiérarchie de catégories comme les catégories de billets.
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'categorie-client'), // Réglez le slug comme vous le souhaitez.
    );

    register_taxonomy('categorie_client', 'fiche_client', $args); // 'fiche_client' est le type de publication personnalisé auquel vous souhaitez attacher la taxonomie.
}

function supprimer_client() {
    if (isset($_POST['client_id']) && is_numeric($_POST['client_id'])) {
        $client_id = intval($_POST['client_id']);
        $client = get_post($client_id);

        if ($client && $client->post_type === 'fiche_client') {
            // Supprimer le client
            wp_delete_post($client_id);

            // Indiquez que la suppression a réussi
            echo 'success';
        } else {
            // Indiquez qu'il y a eu une erreur
            echo 'error';
        }
    } else {
        // Indiquez qu'il y a eu une erreur
        echo 'error';
    }

    // Assurez-vous de quitter le script après avoir renvoyé la réponse
    wp_die();
}

add_action('wp_ajax_supprimer_client', 'supprimer_client');

add_action('init', 'enregistrer_taxonomy_client');

add_action('admin_menu', 'mon_plugin_menu');
?>
