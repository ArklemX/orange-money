pipeline {
    agent any

    stages {
        stage('echo') {
            steps {
               echo 'Clone OM'
            }
        }
        stage('git pull') {
            steps {
				bat 'cd /d D:'
				bat 'dir'
				bat 'git pull https://github.com/ArklemX/orange-money.git main'
            }
        }
    }
}
