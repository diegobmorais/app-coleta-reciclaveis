describe('Funcionalidade: Criar Novo Agendamento', () => {
    const baseUrl = 'http://localhost:5180'

    // valida se data tem pelo menos 2 dias úteis após a data atual
    function getDataSugerida() {
        const hoje = new Date()
        let data = new Date(hoje)
        let diasUteisAdicionados = 0

        while (diasUteisAdicionados = 2) {
            data.setDate(data.getDate() + 1)
            const diaSemana = data.getDay()
            if (diaSemana !== 0 && diaSemana !== 6) {
                diasUteisAdicionados++
            }
        }

        while (data.getDay() === 0 || data.getDay() === 6) {
            data.setDate(data.getDate() + 1)
        }

        const ano = data.getFullYear()
        const mes = String(data.getMonth() + 1).padStart(2, '0')
        const dia = String(data.getDate()).padStart(2, '0')
        return `${ano}-${mes}-${dia}`
    }

    const dataSugerida = getDataSugerida()

    beforeEach(() => {
        cy.visit(baseUrl)
    })

    it('deve criar um novo agendamento com sucesso', () => {
        const nome = 'Debora'

        function formatarData(dataString) {
            const [ano, mes, dia] = dataString.split('-')
            return `${dia}/${mes}/${ano}`
        }

        cy.get('input[placeholder="Seu nome completo"]').type('Debora')
        cy.get('input[placeholder="Ex: Rua das Flores"]').type('Rua das Acácias')
        cy.get('input[placeholder="123"]').type('456')
        cy.get('input[placeholder="Jardim Primavera"]').type('Jardim Primavera')
        cy.get('input[placeholder="São Paulo"]').type('Contagem - MG')


        cy.get('input[type="date"]').type(dataSugerida)

        cy.get('input[placeholder="(11) 98765-4321"]').type('(31) 99876-5432')
        cy.get('input[placeholder="seu@email.com"]').type('fulano@email.com')
        cy.get('textarea[placeholder="Ex: Deixar na portaria, horário comercial, etc."]').type(
            'Gostaria de agendar para sábado, se possível.'
        )

        cy.get('[data-testid="toggle-materials"]').click()

        cy.get('[data-testid="material-checkbox-5"]').should('be.visible').check()
        cy.get('[data-testid="material-checkbox-4"]').should('be.visible').check()

        cy.intercept('POST', 'http://localhost:8000/api/appointments').as('getAgendamento')
        cy.get('button[type="submit"]').contains('Agendar Coleta').click()

        cy.wait('@getAgendamento')
        cy.url().should('include', '/success')
        cy.contains('Seu pedido de coleta foi registrado com sucesso.', { timeout: 10000 }).should('be.visible')

        cy.get('p').contains(/Protocolo: [A-Z]{6}-[A-Z0-9]{6}/).should('be.visible')
        cy.contains(`Nome: ${nome}`).should('be.visible')
        cy.contains(`Data: ${formatarData(dataSugerida)}`).should('be.visible')
    })
})