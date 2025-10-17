#!/bin/bash

echo "=== DIAGNOSTIC SONARQUBE ==="
echo "Host: http://192.168.50.4:9000"
echo "============================="

# Test 1: Vérifier la connectivité
echo "1. Test de connectivité SonarQube..."
if curl -f -s "http://192.168.50.4:9000/api/system/status" > /dev/null; then
    echo "✅ SonarQube est accessible"
    curl -s "http://192.168.50.4:9000/api/system/status" | grep -o '"status":"[^"]*"' || echo "Status: OK"
else
    echo "❌ SonarQube n'est pas accessible"
    exit 1
fi

# Test 2: Vérifier les projets existants
echo -e "\n2. Test de l'API des projets..."
if curl -f -s "http://192.168.50.4:9000/api/projects/search" > /dev/null; then
    echo "✅ API des projets accessible"
    PROJECT_COUNT=$(curl -s "http://192.168.50.4:9000/api/projects/search" | grep -o '"total":[0-9]*' | cut -d':' -f2)
    echo "Nombre de projets: $PROJECT_COUNT"
    
    if [ "$PROJECT_COUNT" -gt 0 ]; then
        echo "Projets existants:"
        curl -s "http://192.168.50.4:9000/api/projects/search" | grep -o '"key":"[^"]*"' | cut -d'"' -f4
    fi
else
    echo "❌ API des projets non accessible"
fi

# Test 3: Vérifier les plugins
echo -e "\n3. Vérification des plugins..."
if curl -f -s "http://192.168.50.4:9000/api/plugins/installed" > /dev/null; then
    echo "✅ API des plugins accessible"
    if curl -s "http://192.168.50.4:9000/api/plugins/installed" | grep -q "php"; then
        echo "✅ Plugin PHP installé"
    else
        echo "⚠️  Plugin PHP non détecté"
    fi
else
    echo "❌ API des plugins non accessible"
fi

# Test 4: Vérifier les logs SonarQube
echo -e "\n4. Vérification des logs SonarQube..."
echo "Pour voir les logs du conteneur SonarQube:"
echo "docker logs sonarqube --tail 50"

# Test 5: Test de création de projet manuel
echo -e "\n5. Test de création de projet manuel..."
echo "URL pour créer un projet: http://192.168.50.4:9000/projects/create"

echo -e "\n=== RÉSUMÉ ==="
echo "SonarQube Host: http://192.168.50.4:9000"
echo "Interface Web: http://192.168.50.4:9000/projects"
echo "Projet attendu: releaf"
echo "==============="

echo -e "\n=== COMMANDES UTILES ==="
echo "Voir les logs SonarQube: docker logs sonarqube --tail 50"
echo "Redémarrer SonarQube: docker restart sonarqube"
echo "Vérifier les plugins: http://192.168.50.4:9000/admin/marketplace"
echo "=========================="
