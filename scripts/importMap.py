def importMap():
    return {
        41: ("Right-Cerebral-White-Matter", "white matter", "subcortical", "right"),
        2: ("Left-Cerebral-White-Matter", "white matter", "subcortical", "left"),

        1028: ("ctx-lh-superiorfrontal", "frontal", "cortical", "left"),
        2028: ("ctx-rh-superiorfrontal", "frontal", "cortical", "right"),
        1027: ("ctx-lh-rostralmiddlefrontal", "frontal", "cortical", "left"),
        2027: ("ctx-rh-rostralmiddlefrontal", "frontal", "cortical", "right"),
        1003: ("ctx-lh-caudalmiddlefrontal", "frontal", "cortical", "left"),
        2003: ("ctx-rh-caudalmiddlefrontal", "frontal", "cortical", "right"),

        1031: ("ctx-lh-supramarginal", "parietal", "cortical", "left"),
        2031: ("ctx-rh-supramarginal", "parietal", "cortical", "right"),
        1025: ("ctx-lh-precuneus", "parietal", "cortical", "left"),
        2025: ("ctx-rh-precuneus", "parietal", "cortical", "right"),
        2029: ("ctx-rh-superiorparietal", "parietal", "cortical", "right"),
        1029: ("ctx-lh-superiorparietal", "parietal", "cortical", "left"),
        1008: ("ctx-lh-inferiorparietal", "parietal", "cortical", "left"),
        2008: ("ctx-rh-inferiorparietal", "parietal", "cortical", "right"),

        1018: ("ctx-lh-parsopercularis", "frontal", "cortical", "left"),
        2018: ("ctx-rh-parsopercularis", "frontal", "cortical", "right"),
        1020: ("ctx-lh-parstriangularis", "frontal", "cortical", "left"),
        2020: ("ctx-rh-parstriangularis", "frontal", "cortical", "right"),
        1019: ("ctx-lh-parsorbitalis", "frontal", "cortical", "left"),
        2019: ("ctx-rh-parsorbitalis", "frontal", "cortical", "right"),
        1024: ("ctx-lh-precentral", "frontal", "cortical", "left"),
        2024: ("ctx-rh-precentral", "frontal", "cortical", "right"),
        1017: ("ctx-lh-paracentral", "frontal", "cortical", "left"),
        2017: ("ctx-rh-paracentral", "frontal", "cortical", "right"),

        2030: ("ctx-rh-superiortemporal", "temporal", "cortical", "right"),
        1030: ("ctx-lh-superiortemporal", "temporal", "cortical", "left"),
        2015: ("ctx-rh-middletemporal", "temporal", "cortical", "right"),
        1015: ("ctx-lh-middletemporal", "temporal", "cortical", "left"),
        2009: ("ctx-rh-inferiortemporal", "temporal", "cortical", "right"),
        1009: ("ctx-lh-inferiortemporal", "temporal", "cortical", "left"),
        2034: ("ctx-rh-transversetemporal", "temporal", "cortical", "right"),

        2011: ("ctx-rh-lateraloccipital", "occipital", "cortical", "right"),
        1011: ("ctx-lh-lateraloccipital", "occipital", "cortical", "left"),
        2005: ("ctx-rh-cuneus", "occipital", "cortical", "right"),
        1005: ("ctx-lh-cuneus", "occipital", "cortical", "left"),
        2021: ("ctx-rh-pericalcarine", "occipital", "cortical", "right"),

        1035: ("ctx-lh-insula", "insular", "cortical", "left"),
        2035: ("ctx-rh-insula", "insular", "cortical", "right"),

        53: ("Right-Hippocampus", "limbic", "subcortical", "right"),
        17: ("Left-Hippocampus", "limbic", "subcortical", "left"),

        18: ("Left-Amygdala", "limbic", "subcortical", "left"),

        1010: ("ctx-lh-isthmuscingulate", "limbic", "cortical", "left"),
        2010: ("ctx-rh-isthmuscingulate", "limbic", "cortical", "right"),
        1023: ("ctx-lh-posteriorcingulate", "limbic", "cortical", "left"),
        2023: ("ctx-rh-posteriorcingulate", "limbic", "cortical", "right"),
        1002: ("ctx-lh-caudalanteriorcingulate", "limbic", "cortical", "left"),
        2002: ("ctx-rh-caudalanteriorcingulate", "limbic", "cortical", "right"),
        1026: ("ctx-lh-rostralanteriorcingulate", "limbic", "cortical", "left"),
        2026: ("ctx-rh-rostralanteriorcingulate", "limbic", "cortical", "right"),

        1014: ("ctx-lh-medialorbitofrontal", "frontal", "cortical", "left"),
        2014: ("ctx-rh-medialorbitofrontal", "frontal", "cortical", "right"),
        1012: ("ctx-lh-lateralorbitofrontal", "frontal", "cortical", "left"),
        2012: ("ctx-rh-lateralorbitofrontal", "frontal", "cortical", "right"),

        1006: ("ctx-lh-entorhinal", "limbic", "cortical", "left"),
        2006: ("ctx-rh-entorhinal", "limbic", "cortical", "right"),
        2016: ("ctx-rh-parahippocampal", "limbic", "cortical", "right"),

        77: ("WM-hypointensities", "white matter", "hypointensities", "both"),

        12: ("Left-Putamen", "subcortical", "subcortical", "left"),
        43: ("Right-Lateral-Ventricle", "ventricular", "csf", "right"),
        44: ("Right-Inf-Lat-Vent", "ventricular", "csf", "right"),
        5: ("Left-Inf-Lat-Vent", "ventricular", "csf", "left"),

        63: ("Right-choroid-plexus", "ventricular", "choroid plexus", "right"),
        60: ("Right-VentralDC", "subcortical", "subcortical", "right"),
        49: ("Right-Thalamus", "subcortical", "subcortical", "right"),
        50: ("Right-Caudate", "subcortical", "basal ganglia", "right"),

        47: ("Right-Cerebellum-Cortex", "cerebellar", "cortical", "right"),
        8: ("Left-Cerebellum-Cortex", "cerebellar", "cortical", "left"),
        51: ("Right-Putamen", "subcortical", "basal ganglia", "right"),
        52: ("Right-Pallidum", "subcortical", "basal ganglia", "right"),
        54: ("Right-Amygdala", "temporal", "amygdala", "right"),
        58: ("Right-Accumbens-area", "subcortical", "accumbens area", "right"),

        1007: ("ctx-lh-fusiform", "temporal", "cortical", "left"),
        1022: ("ctx-lh-postcentral", "parietal", "cortical", "left"),
        1035: ("ctx-lh-transversetemporal", "temporal", "cortical", "left"),

        2007: ("ctx-rh-fusiform", "temporal", "cortical", "right"),
        2022: ("ctx-rh-postcentral", "parietal", "cortical", "right"),
        2035: ("ctx-rh-transversetemporal", "temporal", "cortical", "right"),

        251: ("CC_Posterior", "commissural", "corpus callosum", "both"),
        255: ("CC_Anterior", "commissural", "corpus callosum", "both"),
        2013: ("ctx-rh-lingual", "occipital", "cortical", "right"),
        4: ("Left-Lateral-Ventricle", "ventricular", "csf", "left"),
        7: ("Left-Cerebellum-White-Matter", "cerebellar", "white matter", "left"),
        10: ("Left-Thalamus", "subcortical", "basal ganglia", "left"),
        11: ("Left-Caudate", "subcortical", "basal ganglia", "left"),
        13: ("Left-Pallidum", "subcortical", "basal ganglia", "left"),
        0: ("Unknown", "unknown", "unknown", "n/a"),
    }
