# La Boutique Française

Bienvenue sur **La Boutique Française**, une application e-commerce développée avec le framework Symfony.

## 🚀 Fonctionnalités

- Gestion des utilisateurs (inscription, connexion, réinitialisation de mot de passe)
- Tunnel d'achat complet (panier, choix de l'adresse, transporteur, paiement)
- Gestion des commandes et des factures
- Système de wishlist pour les utilisateurs
- Interface d'administration pour la gestion des commandes et des produits
- Notifications par email (confirmation d'inscription, état des commandes)

## 🌟 Points forts

- **Expérience utilisateur optimisée** : Interface intuitive et responsive pour une navigation fluide.  
- **Sécurité renforcée** : Gestion sécurisée des utilisateurs et des paiements.  
- **Personnalisation** : Possibilité d'ajouter des fonctionnalités spécifiques selon les besoins des commerçants.  
- **Administration simplifiée** : Interface d'administration claire pour gérer les produits, commandes et utilisateurs.  

## 🎯 Objectifs du projet

1. Fournir une solution e-commerce clé en main pour les petites et moyennes entreprises.  
2. Offrir une expérience utilisateur moderne et fluide.  
3. Intégrer des outils de gestion performants pour les administrateurs.  
4. Garantir la sécurité des données des utilisateurs et des transactions.  

## 📊 Compétences, frameworks et API utilisés

- **Symfony (Backend)** : 50%  
- **Twig (Frontend)** : 20%  
- **JavaScript & CSS** : 15%  
- **Doctrine ORM (Base de données)** : 10%  
- **Mailjet (API d'envoi d'emails)** : 5%  

## 🛠️ Installation

1. Clonez le dépôt :

   ```bash
   git clone <url-du-repo>
   cd la_boutique_francaise
   ```

2. Installez les dépendances PHP et JavaScript :

   ```bash
   composer install
   npm install
   ```

3. Configurez les variables d'environnement :
   - Copiez le fichier `.env` :

   ```bash
   cp .env .env.local
   ```

   - Modifiez les valeurs dans `.env.local` pour correspondre à votre environnement (base de données, clés API, etc.)

4. Créez la base de données et appliquez les migrations :

   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

5. Compilez les assets :

   ```bash
   npm run dev
   ```

6. Lancez le serveur Symfony :

   ```bash
   symfony server:start
   ```

## 📂 Structure du projet

- **src** : Contient le code source de l'application (contrôleurs, entités, formulaires, services, etc.).
- **templates** : Fichiers Twig pour les vues.
- **assets** : Fichiers front-end (JavaScript, CSS, images).
- **migrations** : Fichiers de migration pour la base de données.
- **tests** : Tests automatisés.

## 📧 Notifications par email

L'application utilise Mailjet pour l'envoi des emails. Configurez vos clés API dans le fichier `.env.local` :

```bash
MJ_APIKEY_PUBLIC=your_public_key
MJ_APIKEY_PRIVATE=your_private_key
```

## 🧪 Tests

Pour exécuter les tests, utilisez la commande suivante :

```bash
php bin/phpunit
```

## 📜 Licence

Ce projet est sous licence MIT. Consultez le fichier LICENSE pour plus d'informations.

Développé avec ❤️ par l'équipe La Boutique Française.
