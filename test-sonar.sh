#!/bin/bash

# Script de test pour vérifier la connectivité SonarQube
# Usage: ./test-sonar.sh

SONAR_HOST="http://192.168.50.4:9000"
PROJECT_KEY="releaf"

echo "=== TEST DE CONNECTIVITÉ SONARQUBE ==="
echo "Host: $SONAR_HOST"
echo "Project Key: $PROJECT_KEY"
echo "======================================"

# Test 1: Vérifier si SonarQube est accessible
echo "1. Test de connectivité SonarQube..."
if curl -f -s "$SONAR_HOST/api/system/status" > /dev/null; then
    echo "✅ SonarQube est accessible"
    curl -s "$SONAR_HOST/api/system/status" | grep -o '"status":"[^"]*"' || echo "Status: OK"
else
    echo "❌ SonarQube n'est pas accessible"
    exit 1
fi

# Test 2: Vérifier l'API des projets
echo -e "\n2. Test de l'API des projets..."
if curl -f -s "$SONAR_HOST/api/projects/search" > /dev/null; then
    echo "✅ API des projets accessible"
    PROJECT_COUNT=$(curl -s "$SONAR_HOST/api/projects/search" | grep -o '"total":[0-9]*' | cut -d':' -f2)
    echo "Nombre de projets: $PROJECT_COUNT"
else
    echo "❌ API des projets non accessible"
fi

# Test 3: Vérifier si le projet existe
echo -e "\n3. Vérification du projet '$PROJECT_KEY'..."
if curl -f -s "$SONAR_HOST/api/projects/search?projects=$PROJECT_KEY" | grep -q "$PROJECT_KEY"; then
    echo "✅ Projet '$PROJECT_KEY' existe dans SonarQube"
else
    echo "❌ Projet '$PROJECT_KEY' n'existe pas dans SonarQube"
    echo "Le projet sera créé lors de la première analyse"
fi

# Test 4: Vérifier les plugins PHP
echo -e "\n4. Vérification des plugins PHP..."
if curl -f -s "$SONAR_HOST/api/plugins/installed" | grep -q "php"; then
    echo "✅ Plugin PHP installé"
else
    echo "⚠️  Plugin PHP non détecté (peut être normal)"
fi

echo -e "\n=== RÉSUMÉ ==="
echo "SonarQube Host: $SONAR_HOST"
echo "Interface Web: $SONAR_HOST/projects"
echo "Projet attendu: $PROJECT_KEY"
echo "==============="

echo -e "\nPour voir le projet après analyse:"
echo "1. Allez sur: $SONAR_HOST/projects"
echo "2. Recherchez: $PROJECT_KEY"
echo "3. Ou allez directement: $SONAR_HOST/dashboard?id=$PROJECT_KEY"
