import { HashConnect, HashConnectConnectionState } from "hashconnect";
import { LedgerId } from "@hashgraph/sdk";
// Main thread
(function () {
    "use strict";

    // console.log("core!");

    setupConnectButtons();

    // Set up connect button event listeners
    function setupConnectButtons() {
        const connectButtons = document.querySelectorAll(".hashpress-btn.connect");
        connectButtons.forEach((button) => {
            button.addEventListener("click", async () => handleConnectButtonClick(button));
        });
    }

    // Handle individual connect button clicks
    async function handleConnectButtonClick(button) {
        const network = button.dataset.network;

        if (!window.pairingData) {
            await initializeHashconnect(network);
        } else {
            await window.hashconnect.disconnect();
        }
    }

    // Initialize HashConnect and set up events
    async function initializeHashconnect(network) {
        window.hashconnect = null;

        try {
            const { appMetadata, projectId } = await fetchAppMetadataAndProjectId();

            if (appMetadata && projectId) {
                // Re-integrate your original line where HashConnect is initialized
                const debugMode = true;
                window.hashconnect = new HashConnect(
                    getNetworkId(network), // Network ID
                    projectId, // The project ID you fetched
                    appMetadata, // App metadata
                    debugMode, // Debug mode
                );

                // Register event listeners
                setUpHashConnectEvents();

                // Initialize and open pairing if not yet paired
                await window.hashconnect.init();
                if (!window.pairingData) window.hashconnect.openPairingModal();
            }
        } catch (error) {
            console.error("Initialization error:", error);
        }
    }

    // Fetch metadata and project_id together
    async function fetchAppMetadataAndProjectId() {
        try {
            const response = await fetch(hashpressCoreData.getProjectSettings, {
                method: "GET",
                headers: {
                    "X-WP-Nonce": hashpressCoreData.nonce,
                },
            });

            if (!response.ok) throw new Error("Failed to fetch data");

            const data = await response.json();
            return {
                appMetadata: {
                    name: data.name,
                    description: data.description,
                    icons: [data.icon],
                    url: data.url,
                },
                projectId: data.project_id,
            };
        } catch (error) {
            console.error("Error fetching app metadata and project ID:", error);
            return {};
        }
    }

    // Set up HashConnect events
    function setUpHashConnectEvents() {
        window.hashconnect.pairingEvent.on(handlePairingEvent);
        window.hashconnect.disconnectionEvent.on(handleDisconnectionEvent);
        window.hashconnect.connectionStatusChangeEvent.on((connectionStatus) => {
            window.state = connectionStatus;
        });
    }

    // Handle pairing event
    function handlePairingEvent(newPairing) {
        window.pairingData = newPairing;
        updateConnectButtonText("disconnectText");
        displayAccountId(newPairing.accountIds[0]);
    }

    // Handle disconnection event
    function handleDisconnectionEvent() {
        updateConnectButtonText("connectText");
        window.pairingData = null;
        displayAccountId();
    }

    // Update connect button text based on status
    function updateConnectButtonText(statusTextKey) {
        const connectButtons = document.querySelectorAll(".hashpress-btn.connect");
        connectButtons.forEach((button) => {
            if (!window.pairingData.network || button.dataset.network === window.pairingData.network) {
                button.firstElementChild.innerText = button.dataset[statusTextKey];
            }
        });
    }

    // Utility to get network ID based on network name
    function getNetworkId(network) {
        const NETWORK_MAP = {
            mainnet: LedgerId.MAINNET,
            previewnet: LedgerId.PREVIEWNET,
            testnet: LedgerId.TESTNET,
        };
        return NETWORK_MAP[network] || LedgerId.TESTNET;
    }

    function displayAccountId(accountId) {
        let accountDisplays = document.querySelectorAll(".hashpress-account");
        [...accountDisplays].forEach((accountDisplay) => {
            if (accountId) {
                accountDisplay.innerHTML = accountId;
                accountDisplay.classList.add("is-active");
            } else {
                accountDisplay.innerHTML = "";
                accountDisplay.classList.remove("is-active");
            }
        });
    }

    window.initializeHashconnect = initializeHashconnect;
})();
